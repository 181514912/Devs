<?php
    /*
    Plugin Name: Demo Plugin
    Plugin URI:
    Description: Plugin to demeonstrate input and output from database
    Author: Ronij Pandey
    Version: 1.0
    Author URI:
    */
	
	//installation function
	function demo_install(){
		global $wpdb;
		global $demo_version;
		$demo_version='1.0';
		
		//demo user table as demo_users
		
		$table_name=$wpdb->prefix.'demo_users';		//database table name - demo_users
		 $charset_collate = $wpdb->get_charset_collate();
		 
		$sql="CREATE TABLE ".$table_name." (
				id int(11) NOT NULL AUTO_INCREMENT,
				uname VARCHAR(50) NOT NULL,
				uemail VARCHAR(50) NOT NULL,
				PRIMARY KEY  (id)
				) $charset_collate;";
	
		require_once(ABSPATH.'wp-admin/includes/upgrade.php');
		dbDelta( $sql );
		add_option("demo_version",$demo_version);
	}
	//Register all hooks
	register_activation_hook( __FILE__,'demo_install');
	
	add_action('admin_menu','demo_actions');	//adding to setting
	
	//function to be calles when plugin is opened
	
	function demo_actions(){
		add_options_page('DemoPlugin','DemoPlugin','manage_options', __FILE__,'demo_admin');
	}
	
	function demo_admin(){		//function generating page contents
?>
		<div class="wrap">
		<h3>This page will input data from user,store them in database and display there name and email</h3>
		<br/>
		<h4>Enter Name and Email Id and click Submit</h4>
		<br/>
		<form action="" method="POST">
		Name:&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
		<input type="text" name="demo_uname" value="">
		<br>
		Email ID:&nbsp&nbsp&nbsp
		<input type="email" name="demo_uemail" value="">
		<br><br>
		<input type="submit" name="demo_submit" value="Submit" class="button-primary"/>
		</form>
		<br/>
		<table class="widefat">
		<thead>
		<tr>
		<th>Serial No.</th>
		<th>Name</th>
		<th>Email ID</th>
		</tr>
		</thead>
		<tfoot>
		<tr>
		<th>Serial No.</th>
		<th>Name</th>
		<th>Email ID</th>
		</tr>
		</tfoot>
		<tbody>
<?php
		//inserting values inside table
		
		global $wpdb;
		$table_name=$wpdb->prefix.'demo_users';		//storing database table name
		$uname=$_POST['demo_uname'];
		$uemail=$_POST['demo_uemail'];
		
		if(isset($_POST['demo_submit'])){
			if($uname=="" || $uemail==""){	//checking for null values
				echo "Enter name and email id";
			}
			else{
				$sql=$wpdb->insert($table_name,array('uname'=>$uname,
													'uemail'=>$uemail));
				if($sql){															//displaying updated contents
					$result = $wpdb->get_results ( "SELECT * FROM ".$table_name );
					foreach ( $result as $print )   {
?>
					<tr>
<?php
					echo "<td>".$print->id."</td>";
					echo "<td>".$print->uname."</td>";
					echo "<td>".$print->uemail."</td>";
?>
					</tr>
<?php
					}
				}
				else{
					echo "Error, try later";
				}
			}
		}
		else{		//displaying content on page load
			$result = $wpdb->get_results ( "SELECT * FROM ".$table_name );
			foreach ( $result as $print )   {
?>
				<tr>
<?php
				echo "<td>".$print->id."</td>";
				echo "<td>".$print->uname."</td>";
				echo "<td>".$print->uemail."</td>";
?>
				</tr>
<?php
			}
		}
?>

		</tbody>
		</div>
<?php
	}
?>
	