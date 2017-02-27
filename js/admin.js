//var baseurl = window.location.origin + '/ebanking/'; remote server settings
var getUrl = window.location;
var baseurl = getUrl.protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1] + "/";
//var baseurl = window.location.origin + '/cyclos/';
var email = $("input[name=email]").val();
var username = $("input[name=username]").val();
// Get the modal
var modal = document.getElementById('myModal');
var select_accounts = document.getElementById('select-accounts');
// Get the button that opens the modal
var upload_new_photo = document.getElementById("upload-btn");
var upload_new_sig = document.getElementById("upload-sig-btn");
var grant_loan = document.getElementById("grant-loan-lnk");
var request_loan = document.getElementById("request-loan-lnk");
var add_contact = document.getElementById("add-contact-lnk");
var view_transactions = document.getElementById("view-transactions-lnk");
var send_sms = document.getElementById("send-sms-lnk");
var loan_select = document.getElementById('loan_duration');
var span = document.getElementById("close");
var interest = 15;

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
	if (event.target === modal) {
		modal.style.display = "none";
	}
};

$('body').on('focus', ".datepicker", function() {
	$(this).datepicker({
		changeMonth: true,
		changeYear: true,
		numberOfMonths: [1, 1],
		yearRange: '1945:' + (new Date).getFullYear(),
		showAnim: "slide",
		appendText: "(yy-mm-dd)",
		dateFormat: "yy-mm-dd"
	});
});

/*******************************************************************************
 ******************************* WITHDRAWAL FUNCTIONS **************************
 *******************************************************************************/
function withdraw_click(username) {
	$.ajax({
		url: baseurl + "withdraw/show_withdraw_fields/" + username,
		success: function(result) {
			modal.style.display = "block";
			$('.modal-content').html(result);
		},
		error: function(result, status, error) {
			modal.style.display = "block";
			$('.modal-content').html(status + " " + error);
		}
	});
}

function showWithdrawInfo(username) {

	var username = username;
	var withdraw_amount = document.getElementById("withdraw_amount").value;

	if (withdraw_amount !== "" && username !== "") {
		$.ajax({
			url: baseurl + "withdraw/get_withdraw_info/" + username + "/" + withdraw_amount,
			type: "POST",
			dataType: "JSON",
			success: function(data) {
				generate_withdrawal_info(data);
			},
			error: function(result, status, error) {
				$('#transaction-info').html("Error: " + status + " Error: " + error);
			}
		});
	}
}

function generate_withdrawal_info(data) {
	var content = '<h3>Account informations:</h3>';
	content += '<p>Users Savings: ' + data['user_savings'] + '</p>';
	content += '<p>Previous Withdrawal: ' + data['users_withdrawals'] + '</p>';
	content += '<p>Users Balance: ' + data['total_balance'] + '</p>';
	content += '<hr style="background:#F87431; border:0; height:2px" />';
	content += '<p>Withdraw Fee:' + data['fees'] + '</p>';
	content += '<p>Total Withdrawn Amount: ' + data['f_total_amount'] + '</p>';
	content += '<input type="hidden" name="total_amount" value="' + data['total_amount'] + '">';
	content += '<input type="hidden" name="charge_amount" value="' + data['fees'] + '">';

	if (data['allowed']) {
		$('#submit').prop('disabled', false);
	} else {
		$('#submit').prop('disabled', true);
		content += '<p class="error">Not enough balance to withdraw.';
		content += '<p class="error">Please view <a href="' + baseurl + "admin/accounts/details/" + data['user_id'] + '">account information</a></p>';
	}

	$('#transaction-info').html(content);
}

function withdrawal_form_submit(event, username) {
	var total_amount = document.getElementsByName("total_amount")[0].value;
	var charge_amount = document.getElementsByName("charge_amount")[0].value;
	var mobile_number = document.getElementById('mobile-phone').innerHTML;
	$.ajax({
		url: baseurl + "withdraw/do_withdraw/" + username,
		type: "POST",
		data: {
			total_amount: total_amount,
			charge_amount: charge_amount,
			mobile_number: mobile_number
		},
		success: function(result) {
			$('.modal-content').html(result);
		},
		error: function(result, status, error) {
			$('.modal-content').html("Error: " + status + " Error: " + error);
		}
	});

	// Prevent form submission
	event.preventDefault();
}

/*******************************************************************************
 ********************************** LOANS FUNCTIONS ****************************
 *******************************************************************************/
function grant_loan_click(username) {
	$.ajax({
		url: baseurl + "loan/grant_loan_fields/" + username,
		success: function(result) {
			modal.style.display = "block";
			$('.modal-content').html(result);
		},
		error: function(result, status, error) {
			modal.style.display = "block";
			$('.modal-content').html(status + " " + error);
		}
	});
}

function showLoanInfo(username) {
	var loan_repayment = document.getElementById("loan_repayment").value;
	var loan_amount = document.getElementById("loan_amount").value;

	if (loan_repayment !== "" && loan_amount !== "") {
		$.ajax({
			url: baseurl + "loan/get_loan_info/" + username + "/" + loan_repayment + "/" + loan_amount,
			dataType: "JSON",
			success: function(data) {
				generate_loan_info(data);
			},
			error: function(result, status, error) {
				$('#transaction-info').html("Error: " + status + " Error: " + error);
			}
		});
	}
}

function generate_loan_info(data) {
	var content = '<h3>Loan informations:</h3>';
	content += data['interest'];
	content += '<p>Loan Amount: ' + data['amount'] + '</p>';
	content += '<p>Interest Amount: ' + data['f_interest_amount'] + '</p>';
	content += '<p>Total Loan Amount: ' + data['f_total_amount'] + '</p>';
	content += '<input type="hidden" name="interest_amount" value="' + data['interest_amount'] + '">';
	content += '<input type="hidden" name="total_amount" value="' + data['total_amount'] + '">';
	if (data['allowed']) {
		$('#submit').prop('disabled', false);
	} else {
		$('#submit').prop('disabled', true);
		content += '<p class="error">Account has still pending loan balance.</p>';
		content += '<p class="error">Please view <a href="' + baseurl + "admin/accounts/details/" + data['user_id'] + '">account information</a></p>';
	}
	$('#transaction-info').html(content);
}

function loan_form_submit(event, username) {
	var interest_amount = document.getElementsByName("interest_amount")[0].value;
	var total_amount = document.getElementsByName("total_amount")[0].value;
	var loan_repayment = document.getElementsByName("loan_repayment")[0].value;
	var mobile_number = document.getElementById('mobile-phone').innerHTML;
	$.ajax({
		url: baseurl + "loan/grant_loan/" + username,
		type: "POST",
		data: {
			total_amount: total_amount,
			loan_repayment: loan_repayment,
			interest_amount: interest_amount,
			mobile_number: mobile_number
		},
		success: function(result) {
			$('.modal-content').html(result);
		},
		error: function(result, status, error) {
			$('.modal-content').html("Error: " + status + " Error: " + error);
		}
	});

	// Prevent form submission
	event.preventDefault();
}

/*******************************************************************************
 ******************************* PAY LOANS FUNCTIONS ***************************
 *******************************************************************************/
function pay_loan_click(username) {
	$.ajax({
		url: baseurl + "loan/pay_loan_fields/" + username,
		success: function(result) {
			modal.style.display = "block";
			$('.modal-content').html(result);
		},
		error: function(result, status, error) {
			modal.style.display = "block";
			$('.modal-content').html(status + " " + error);
		}
	});
}

function showPayLoanInfo(username) {
	var pay_amount = document.getElementById("pay_amount").value;
	if (pay_amount !== "") {
		$.ajax({
			url: baseurl + "loan/pay_loan_info/" + username + "/" + pay_amount,
			dataType: "JSON",
			success: function(data) {
				generate_pay_loan_info(data);
			},
			error: function(result, status, error) {
				$('#transaction-info').html("Error: " + status + " Error: " + error);
			}
		});
	}
}

function generate_pay_loan_info(data) {
	var content = '<h3>Loan informations:</h3>';
	content += '<p>Total Loan: ' + data['total_loan'] + '</p>';
	content += '<p>Pay Amount: ' + data['f_amount'] + '</p>';
	content += '<p>Loan Balance: ' + data['f_loan_balance'] + '</p>';
	content += '<input type="hidden" name="loan_balance" value="' + data['loan_balance'] + '">';
	content += '<input type="hidden" name="total_amount" value="' + data['amount'] + '">';
	$('#transaction-info').html(content);
}

function pay_loan_submit(event, username) {
	var pay_amount = document.getElementsByName("pay_amount")[0].value;
	var loan_balance = document.getElementsByName("loan_balance")[0].value;
	var mobile_number = document.getElementById('mobile-phone').innerHTML;
	$.ajax({
		url: baseurl + "loan/pay_loan/" + username,
		type: "POST",
		data: {
			amount: pay_amount,
			loan_balance: loan_balance,
			mobile_number: mobile_number
		},
		success: function(result) {
			$('.modal-content').html(result);
		},
		error: function(result, status, error) {
			$('.modal-content').html("Error: " + status + " Error: " + error);
		}
	});

	// Prevent form submission
	event.preventDefault();
}

/*******************************************************************************
 ******************************** DEPOSITS FUNCTIONS ***************************
 *******************************************************************************/
function deposit_click(username) {
	$.ajax({
		url: baseurl + "deposit/deposit_fields/" + username,
		success: function(result) {
			modal.style.display = "block";
			$('.modal-content').html(result);
		},
		error: function(result, status, error) {
			modal.style.display = "block";
			$('.modal-content').html(status + " " + error);
		}
	});
}

function showDepositInfo(username) {
	var deposit_amount = document.getElementById("deposit_amount").value;

	if (deposit_amount !== "" && username !== "") {
		$.ajax({
			url: baseurl + "deposit/show_deposit_info/" + username + "/" + deposit_amount,
			dataType: "JSON",
			success: function(data) {
				generate_deposit_info(data);
			},
			error: function(result, status, error) {
				$('#transaction-info').html("Error: " + status + " Error: " + error);
			}
		});
	}
}

function generate_deposit_info(data) {
	var content = '<h3>Deposit Informations:</h3>';
	content += '<p>Previous Deposits: ' + data['user_deposits'] + '</p>';
	content += '<p>To Be Deposit: ' + data['amount'] + '</p>';
	content += '<p>Total Deposits: ' + data['total_balance'] + '</p>';
	$('#transaction-info').html(content);
}

function deposit_form_submit(event, username) {
	var deposit_amount = document.getElementsByName("deposit_amount")[0].value;
	var mobile_number = document.getElementById('mobile-phone').innerHTML;
	$.ajax({
		url: baseurl + "deposit/do_deposit/" + username,
		type: "POST",
		data: {
			amount: deposit_amount,
			mobile_number: mobile_number
		},
		success: function(result) {
			$('.modal-content').html(result);
		},
		error: function(result, status, error) {
			$('.modal-content').html("Error: " + status + " Error: " + error);
		}
	});

	// Prevent form submission
	event.preventDefault();
}

/*******************************************************************************
 ******************************* MESSAGING FUNCTIONS ***************************
 *******************************************************************************/
function selectNumber() {

	var selected = document.getElementsByName("select")[0].value;

	if (selected === 'Males') {
		select_accounts.style.display = "none";
		$.ajax({
			url: baseurl + "sms/get_male_group",
			dataType: "JSON",
			success: function(data) {
				document.getElementsByName("mobile")[0].value = data['numbers'];
			},
			error: function(result, status, error) {
				alert("Error: " + status + " Error: " + error);
			}
		});
	} else if (selected === 'Females') {
		select_accounts.style.display = "none";
		$.ajax({
			url: baseurl + "sms/get_female_group",
			dataType: "JSON",
			success: function(data) {
				document.getElementsByName("mobile")[0].value = data['numbers'];
			},
			error: function(result, status, error) {
				alert("Error: " + status + " Error: " + error);
			}
		});
	} else {
		document.getElementsByName("mobile")[0].value = '';
		select_accounts.style.display = "block";
	}
}

function addPhoneNumber(phone_number) {
	if (document.getElementsByName("mobile")[0].value === "") {
		document.getElementsByName("mobile")[0].value = document.getElementsByName("mobile")[0].value + phone_number;
	} else {
		document.getElementsByName("mobile")[0].value = document.getElementsByName("mobile")[0].value + "," + phone_number;
	}
}

function removePhoneNumber() {
	var checkboxes = document.getElementsByName("accounts");
	for (var i = 0; i < checkboxes.length; i++) {
		checkboxes[i].checked = false;

	}
	document.getElementsByName("mobile")[0].value = "";
}

function send_sms_click(username) {
	var mobile_num = document.getElementById('mobile-phone').innerHTML;
	$.ajax({
		url: baseurl + "sms/show_sms_fields/" + username + "/" + mobile_num,
		success: function(result) {
			modal.style.display = "block";
			$('.modal-content').html(result);
		},
		error: function(result, status, error) {
			modal.style.display = "block";
			$('.modal-content').html(status + " " + error);
		}
	});
}

function send_sms_submit(event) {

	var username = document.getElementsByName("user")[0].value;
	var pass = document.getElementsByName("password")[0].value;
	var number = document.getElementsByName("mobile")[0].value;
	var sender_id = document.getElementsByName("senderid")[0].value;
	var string_message = document.getElementsByName("message")[0].value;

	$.ajax({
		url: "http://www.wivupsms.com/sendsms.php?user=" + username,
		success: function(result) {
			$('.modal-content').html(result);
		},
		error: function(result, status, error) {
			$('.modal-content').html("Error: " + status + " Error: " + error);
			console.log(result);
		}
	});
	// Prevent form submission
	event.preventDefault();
}
/*******************************************************************************
 ******************************* SEARCH FUNCTIONS ******************************
 *******************************************************************************/

function search(e) {
	if (e.keyCode === 13) {
		e.preventDefault();
		$.ajax({
			url: baseurl + "search/do_search/" + document.getElementById("search").value,
			success: function(result) {
				$('.admin-table').html(result);
			},
			error: function(result, status, error) {
				$('.admin-table').html("Status: " + status + " <br>Error: " + error);
			}
		});
	}
}


function s_autocomplete(data) {

	$("#search").autocomplete({

		source: data

	});

}

/*******************************************************************************
 ******************************* PRINTING RECEIPT ******************************
 *******************************************************************************/
function print_receipt() {

	var mywindow = window.open('', '_blank', 'width=245px');
	mywindow.document.write('<html><head><title></title>');
	mywindow.document.write('<link rel="stylesheet" href="' + baseurl + 'css/print.css" type="text/css" media="print"/>');
	mywindow.document.write('</head><body >');
	mywindow.document.write(document.getElementById('receipt').innerHTML);
	mywindow.document.write('</body></html>');

	mywindow.print();
	mywindow.close();

	return true;
}

/*******************************************************************************
 ************************** REGENERATING RECEIPT *******************************
 *******************************************************************************/
function regenerate_receipt_click(id) {
	$.ajax({
		url: baseurl + "receipt/regenerate_receipt/" + id,
		success: function(result) {
			modal.style.display = "block";
			$('.modal-content').html(result);
		},
		error: function(result, status, error) {
			modal.style.display = "block";
			$('.modal-content').html(status + " " + error);
		}
	});

	// Prevent form submission
	event.preventDefault();
}
/*******************************************************************************
 ********************** UPLOADING PHOTO AND SIGNATURE **************************
 *******************************************************************************/
upload_new_photo.onclick = function() {
	modal.style.display = "block";
	var content = '<span id="close">x</span>';
	content += '<form action="' + baseurl + 'upload/upload_image" enctype="multipart/form-data" method="post" accept-charset="utf-8">';
	content += '<fieldset>';
	content += '<legend>Upload Image:</legend>';
	content += '<input type="file" name="userfile" size="20" />';
	content += '<br /><br />';
	content += '<input type="hidden" name="email" value="' + email + '" />';
	content += '<input type="hidden" name="username" value="' + username + '" />';
	content += '<input type="submit" value="Upload Image" />';
	content += '</fieldset>';
	content += '</form>';
	$('.modal-content').html(content);
};

upload_new_sig.onclick = function() {
	modal.style.display = "block";
	var content = '<span id="close">x</span>';
	content += '<form action="' + baseurl + 'upload/upload_signature" enctype="multipart/form-data" method="post" accept-charset="utf-8">';
	content += '<fieldset>';
	content += '<legend>Upload Signature:</legend>';
	content += '<input type="file" name="userfile" size="20" />';
	content += '<br /><br />';
	content += '<input type="hidden" name="email" value="' + email + '" />';
	content += '<input type="hidden" name="username" value="' + username + '" />';
	content += '<input type="submit" value="Upload Signature" />';
	content += '</fieldset>';
	content += '</form>';
	$('.modal-content').html(content);
};
