<?php
session_start();

$infoAlert = "";
$successAlert = "";
$dangerAlert ="";


//check if user has a cookie, user has not logged out.
if(array_key_exists("id", $_COOKIE) && $_COOKIE['id']){

	$_SESSION['id']= $_COOKIE['id'];
}

//if ession is established
if(array_key_exists("id", $_SESSION) && $_SESSION['id']){

	//get the current user name and display in the navbar
	include("connection.php");
	mysqli_set_charset( $link, 'utf8');
	$query = "SELECT * FROM `usertable` WHERE id = ".mysqli_real_escape_string($link, $_SESSION['id'])." LIMIT 1";
	$row1 = mysqli_fetch_array(mysqli_query($link, $query));
	$temp = $row1['email'];
	$emailaddress = explode("@",$temp);
	$user = $emailaddress[0];


	$disableUser="";
	if ($row1['levelgroup']==0){
	$disableUser = "disabled";
	$roUser = "readonly";
	} else if ($row1['levelgroup']==1){
	$disableAdmin = "disabled";	
	//$roAdmin = "readonly";
	}

	//admins.php includeds the modal and submitting code
	include("modalActions.php");
	
} else{
	header("Location: index.php");
}

include("header.php");
?>

<!--Navbar beginning-->
<nav class="navbar transparent navbar-toggleable-sm navbar-inverse bg-primary bg-faded navbar-fixed-top">
	<button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>
	<div class="navbar-brand">KeyBase</div>
	<div class="navbar-brand" id="user"> <?php echo $user; ?></div>
	<div class="collapse navbar-collapse" id="navbarSupportedContent">
		<ul class="navbar-nav mr-auto"></ul>
		<div class="form-inline-right my-2 my-lg-0">
			<a href="#"><button id="add-remove-button" class="btn btn-outline-info my-2 my-sm-0" data-toggle="modal" data-target="#myModal">add/remove</button></a>

			<!--MODAL BEGIN-->
			<div aria-hidden="true" class="modal hide fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				<div class="modal-dialog modal-lg">
					<div class="modal-content">

						<!--Modal header begins-->
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
								X</button>
								<h4 class="modal-title" id="myModalLabel">ADD/REMOVE USERS & ENTRIES</h4>
							</div>
							<!--Modal header ends-->

							<!--Modal body begins-->
							<div class="modal-body">

								<!--Modal Tabs begin-->
								<div class="row">
									<div>

										<!--Modal tab-bottons begin-->
										<ul class="nav nav-tabs" role="tablist">
											<li class="nav-item">
												<a id="entries-tab" class="nav-link" href="#entries" role="tab" data-toggle="tab">Entries</a>
											</li>
											<li class="nav-item">
												<a id="users-tab" class="nav-link active" href="#users" role="tab" data-toggle="tab">Users</a>
											</li>
										</ul>
										<!--Modal tab-bottons end-->

										<!-- Tab panels begin-->
										<div class="tab-content">

											<!--First tab begins-->
											<div class="tab-pane fade in active" role="tabpanel" id="users">
												<form method="post" id="usersForm" role="form" class="form-horizontal col-sm-12">

													<div class="row form-group">
														<div id="full-name" class="col-sm-8">
															<textarea id="name" style="resize: none;" type="text" name="name" rows="1" class="form-control" required="true" placeholder="Full Name" ></textarea>
														</div>
														<div id="modal-levels" class="col-sm-3">
															<textarea id="level" type="text" maxlength="1" style="resize: none;"  name="levelgroup" rows="1" class="form-control" required="true" placeholder="level" ></textarea>
														</div>
													</div>

													<div class="row form-group">
														<div class="col-sm-7">
															<input type="email" name="email" class="form-control" id="modalEmail" placeholder="Email"/>
														</div>
														<div class="col-sm-4">
															<textarea type="text" style="resize: none;" name="mobile" rows="1" class="form-control digitsOnly" id="mobile" maxlength="15" placeholder="Mobile" ></textarea>
														</div>
													</div>  

													<div id="action-buttons-users" class="row form-group action-buttons">
														<div>
															<button id="addUser" name="addUser" value="addUser" type="submit" class="btn btn-success btn-sm smaller">Add</button>
														</div>
														<div>
															<button id="updateUser" type="submit" name="updateUser" value="updateUser" class="btn btn-warning btn-sm smaller">Update</button>
														</div> 

														<div>
															<button id="deleteUser" type="submit" name="deleteUser" value="deleteUser" class="btn btn-danger btn-sm smaller">Delete</button>
														</div>
													</div>
												</form>
											</div>

											<!--First tab ends--> 

											<!-- Second tab begin-->
													
												<!-- Hidden textarea for formatting purposes-->
													<textarea id="hid-lvl" readonly="true" style="height: 0px; border: none; visibility: hidden;" type="text" class="mainForm-item"></textarea>
												

											<div class="tab-pane fade in active" role="tabpanel" id="entries">
												<form method="post" id="entriesForm" role="form" class="form-horizontal form-inline-right col-sm-12">

													<div class="row form-group">
														<div class="col-sm-11">
															<input type="text" name="address" class="form-control" id="modalAddress" required placeholder="Address (exp. Kungsgatan 12)" />
														</div>
													</div>  
													<div class="col-sm-11">
														<input type="text" name="city" value="Uppsala" class="form-control" id="modal-city" style="visibility: hidden; height: 0px; width: 0px; position: absolute; border: none;" />
													</div>

													<div class="row form-group">  
														<div class="col-sm-8">
															<button class="form-control dropdown-toggle" id="modal-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																<span id="selected" >Uppsala&nbsp;</span><span class="caret"></span>
															</button>
															<ul class="dropdown-menu" id="modalDropdown" multiple="multiple" aria-labelledby="modal-dropdown">
																<li><a class="dropdown-item" href="#">Enköping</a></li>
																<li><a class="dropdown-item" href="#">Knivsta</a></li>
																<li><a class="dropdown-item" href="#">Heby</a></li>
																<li><a class="dropdown-item" href="#">Håbo</a></li>
																<li><a class="dropdown-item" href="#">Tierp</a></li>
																<li><a class="dropdown-item" href="#">Upplands Väsby</a></li>
																<li><a class="dropdown-item" href="#">Uppsala</a></li>
																<li><a class="dropdown-item" href="#">Älvkarleby</a></li>
																<li><a class="dropdown-item" href="#">Östhammar</a></li>
															</ul>
														</div>
													
														<div class="col-sm-3">
															<textarea style="resize: none;" rows="1" type="text" id="modalKey" name="keynumber" class="form-control digitsOnly" maxlength="6" placeholder="key" required="true"></textarea>
														</div>
													</div>

													<div class="row form-group">
														<div class="col-sm-11">
															<textarea id="modalTextarea" name="comments" rows="4" placeholder="comments"></textarea>    
														</div>
													</div>

													<div id="action-buttons-entries" class="row form-group">
														<div>
															<button type="submit" name="addEntry" value="add" class="btn btn-success btn-sm smaller"  id="addEntry">Add</button>
														</div>
														<div>
															<button type="submit" id="updateEntry" name="updateEntry" value="update" class="btn btn-warning btn-sm smaller">Update</button>
														</div> 

														<div>
															<button type="submit" id="deleteEntry" name="deleteEntry" value="delete" class="btn btn-danger btn-sm smaller">Delete</button>
														</div>
													</div>
												</form>
											</div>
											<!-- Second tab ends-->

										</div>
										<!--End Tab Panels-->
									</div>
								</div>
							</div>
							<!--End Modal Body-->
						</div>
						<!--End Modal Content-->
					</div>
				</div>
				<!--MODAL END-->


				<a href='index.php?logout=1'><button class="btn btn-outline-info my-2 my-sm-0" type="submit">Logout</button>
				</a>
			</div>	

		</div>
	</nav>
	<!--Navbar end-->


	<!-- Main form for key search-->
	<div id="field-form">

		<div id="message-info">
			<?php 
				if($dangerAlert!="") {
				echo '<div class="alert alert-danger alert-dismissible text-center fade show" role="alert"><button  type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'.$dangerAlert.'</div>';
				} else if ($successAlert!="") {
				echo '<div class="alert alert-success alert-dismissible text-center fade show" role="alert" ><button  type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'.$successAlert.'</div>';
				} else if ($infoAlert!="") {
				echo '<div class="alert alert-info alert-dismissible text-center fade show" role="alert"><button  type="button" class="close" data-dismiss="alert" aria-label="Close"><span  aria-hidden="true">&times;</span></button>'.$infoAlert.'</div>';
				}
			?>
		</div>

		<div class="dropdown">
			<a class="dropdown-toggle btn btn-outline-info" role="button" href="#" id="search-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				<span id="m-selected">Uppsala</span><span class="caret"></span>
			</a>
			<ul id="mainDropdown" class="dropdown-menu" name="city" aria-labelledby="search-dropdown">
				<li><a class="dropdown-item" href="#">Enköping</a></li>
				<li><a class="dropdown-item" href="#">Knivsta</a></li>
				<li><a class="dropdown-item" href="#">Heby</a></li>
				<li><a class="dropdown-item" href="#">Håbo</a></li>
				<li><a class="dropdown-item" href="#">Tierp</a></li>
				<li><a class="dropdown-item" href="#">Upplands Väsby</a></li>
				<li><a class="dropdown-item" href="#">Uppsala</a></li>
				<li><a class="dropdown-item" href="#">Älvkarleby</a></li>
				<li><a class="dropdown-item" href="#">Östhammar</a></li>
			</ul>
		</div>

		<fieldset class="mainForm">
			<img class="icons" src="/images/address.png">
			<textarea id="address" type="text" class="mainForm-item" placeholder="Type address here"></textarea>
		</fieldset>

		<fieldset class="mainForm">
			<img class="icons" src="/images/keynumber.png">
			<textarea readonly="true" id="keyNumber" type="text" class="mainForm-item"></textarea>
		</fieldset>

		<fieldset class="mainForm">
			<img class="icons" id="commenticon" src="/images/comments.png">
			<textarea readonly="true" id="comments" type="text" class="mainForm-item"></textarea>
		</fieldset>
	</div>
	<!--Main key search form end-->



	<?php include("footer.php"); ?>