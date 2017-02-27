<header>
    <h1><?php echo $title; ?></h1>
</header>
<div class="one-column">
    <header><h2>Accounts List</h2></header>


    <div class="search-area">
        Search for?
        <input type="radio" name="field_id" value="13" checked> Account Name
        <input type="radio" name="field_id" value="14"> Accout Number
        <input type="radio" name="field_id" value="8"> Mobile Number
        <input type="radio" name="field_id" value="15"> ID Number
        <input name="search" id="search" placeholder="search" onkeydown="get_hint(event)">
    </div>


    <script type="text/javascript">
    var getUrl = window.location;
    var baseurl = getUrl.protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1] + "/";

      function get_hint(e) {
      	if (e.keyCode === 13) {
      		$.ajax({
      			url: baseurl + "search/do_search/" + document.getElementById("search").value,
      			success: function(result) {
      				$('.admin-table').html(result);
      			},
      			error: function(result, status, error) {
      				$('.admin-table').html("Status: " + status + " <br>Error: " + error);
      			}
      		});
      	} else {
            var word = document.getElementById("search").value;
            var field_id = $("input[name='field_id']:checked").val();
            if (word !== "") {
            	$.ajax({
            		url: baseurl + "search/get_search_data/" + word + "/" + field_id,
            		dataType: "JSON",
            		success: function(data) {
            			s_autocomplete(data);
            		},
            		error: function(result, status, error) {
            			console.log("Error: " + status + " Error: " + error);
            		}
            	});
            }
      	}
      }


      function s_autocomplete(data) {

      	$("#search").autocomplete({

      		source: data

      	});

      }
    </script>
    <div class="admin-table">
        <table>
            <thead>
                <tr>
                    <?php
                    foreach ($accounts as $account) {
                        foreach ($account as $key => $value) {
                            if ($key !== 'id') {
                                echo '<th>' . ucwords(str_replace('_', ' ', $key)) . '</th>';
                            }
                        }
                        break;
                    }
                    ?>
                </tr>
            </thead>
            <tbody>
            <?php
            foreach ($accounts as $account) {
                echo '<tr>';
                foreach ($account as $key => $value) {
                    if ($key !== 'id') {
                        if ($key === 'Account Name') {
                            echo '<td><a href="' . base_url() . 'admin/accounts/details/' . $account['id'] . '">' . ucwords(str_replace('_', ' ', $value)) . '</a></td>';
                        } else {
                            echo '<td>' . ucwords(str_replace('_', ' ', $value)) . '</td>';
                        }
                    }
                }
                echo '</tr>';
            }
            ?>
            </tbody>
        </table>
    </div>
    <p class="paginate-links"><?php echo $links; ?></p>
</div>
