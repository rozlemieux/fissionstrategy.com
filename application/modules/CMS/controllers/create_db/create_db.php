<?php

$blogger_info = array(
    'A' => 'Source',
    'AP' => 'City',
    'AQ' => 'State',
    'AR' => 'Country',
    'AS' => 'Blog_Name',
    'AT' => 'URL',
    'AU' => 'Authority',
    'AV' => 'Title',
    'AW' => 'First_Name',
    'AX' => 'Last_Name',
    'AY' => 'Email',
    'AZ' => 'Email_2',
    'BA' => 'Web_Form_URL',
    'BB' => 'Notes',
    'BC' => 'Twitter_Blogger',
    'BD' => 'Twitter_Blogger_Followers',
    'BE' => 'Twitter_Outlet',
    'BF' => 'Twitter_Outlet_Followers',
    'BG' => 'Facebook',
    'BH' => 'Fans',
    'BI' => 'Phone',
    'BJ' => 'Fax',
    'BK' => 'Additional_Contacts',
    'BL' => 'Skype',
    'BM' => 'Estimated_Readership',
    'BO' => 'Media_Outlet',
    ' '  => 'Twitter'
);

$category = array(
    //        'id' => 'od6',
    'B' => 'African_American',
    'C' => 'Art',
    'D' => 'Asian_American',
    'E' => 'Blogger_of_Color',
    'F' => 'Breast_Cancer',
    'G' => 'Cancer',
    'H' => 'Catholic',
    'I' => 'Climate_Change',
    'J' => 'Conservati0n',
    'K' => 'Conservative',
    'L' => 'Corporate_Accountability',
    'M' => 'Energy',
    'N' => 'Economics',
    'O' => 'Faith',
    'P' => 'Film',
    'Q' => 'Finance',
    'R' => 'Green',
    'S' => 'Green_Building',
    'T' => 'Green_Business',
    'U' => 'Green_Tech',
    'V' => 'Health',
    'W' => 'Human_Rights',
    'X' => 'Immigration',
    'Y' => 'Interfaith',
    'Z' => 'International_Development',
    'AA' => 'Latino',
    'AB' => 'LGBT',
    'AC' => 'Liberal',
    'AD' => 'Lifestyle',
    'AE' => 'Local',
    'AF' => 'Mommy_Blogger',
    'AG' => 'News',
    'AH' => 'Open_Gov',
    'AI' => 'Politics',
    'AJ' => 'State-Based',
    'AK' => 'Sustainable_Development',
    'AL' => 'Technology',
    'AM' => 'Unitarian_Universalist',
    'AN' => 'Women',
    'AO' => 'Youth'
);



$con = mysql_connect(
  ':/Applications/MAMP/tmp/mysql/mysql.sock',
  'root',
  'root'
);

if (!$con) {
    die('Could not connect: ' . mysql_error());
}

$res = mysql_select_db("fissionstrategy", $con);
if  (!$res) echo mysql_error();


//create_fs_blogger($con);
//create_category($con);
//populate_categories($category, $con);
//create_grid($con);
//create_listservs($con);
//create_twitter($con);

import_all($con);

mysql_close($con);


function import_all($con) {
    for ($i = 1; $i < 11; $i++) {
        $spreadsheets = unserialize(file_get_contents("Spreadsheets/Blogger{$i}_worksheet.txt"));
        foreach ($spreadsheets as $sheet_name => $sheet) {
            echo print_r("Spreadsheet " . $sheet_name . "\n");
            $mapping = array();
            foreach ($sheet as $key => $value) {
                if (preg_match('/([A-Z]+)(\d+)/', $key, $key_string)) {
                    $col = $key_string[1];
                    $row = $key_string[2];
                    $mapping[$col] = str_replace(' ', '_', $value);
                    if ($row > 1) break;
                }
            }
            //echo print_r($mapping,1);
            import_sheet ($con, $sheet_name, $mapping, $sheet);
        }
    }
}


function create_twitter($con) {
    $spreadsheets = unserialize(file_get_contents("Blogger0_worksheet.txt"));
    $cat_name = 'Twitter';
    $mapping = array(
        'A' => 'Source',
        'B' => 'Blog_Name',
        'C' => 'Estimated_Readership',
        'D' => 'Authority',
        'E' => 'Twitter_Blogger',
        'F' => 'Notes'
    );
    create_from_sheet ($con, $key, $mapping, $spreadsheets);
}

function create_listservs($con, $i) {

    $spreadsheets = unserialize(file_get_contents("Blogger0_worksheet.txt"));
    $cat_name = 'ListServs';
    $mapping = array(
        'A' => 'Blog_Name',
        'B' => 'URL',
        'C' => 'Estimated_Readership');

    create_from_sheet ($con, $cat_name, $mapping, $spreadsheets);

}

function create_from_sheet ($con, $sheet_name, $mapping, $spreadsheets) {

    $current_row = 0;
    $blogger_id = 0;

    // create a category
    $sql = "insert into fs_blogger_category (name) values ('$sheet_name')";
    mysql_query($sql);

    foreach ($spreadsheets[$sheet_name] as $key => $value) {

        if (preg_match('/([A-Z]+)(\d+)/', $key, $key_string)) {
            $col = $key_string[1];
            $row = $key_string[2];

            if ($row > 1) {
                // create new blogger
                if ($row > $current_row) {
                    $sql = "insert into fs_blogger (Source) values('1') ";
                    echo $sql  . "\n";

                    mysql_query($sql);

                    $blogger_id = mysql_insert_id();
                    $current_row = $row;
                    $sql = "insert into fs_blogger_cat_map (blogger_id, category_id) values ('$blogger_id', (select id from fs_blogger_category where name = '$sheet_name'))";
                    mysql_query($sql);
                    $sql = "update fs_blogger set od6 = '" . $sheet_name . "' where id = " . $blogger_id;
                }
                if (isset($mapping[$col])) {
                    $col_name = $mapping[$col];
                    $sql = "update fs_blogger set " . $col_name . " = '" . $value . "' where id = " . $blogger_id;
                    echo $sql  . "\n";
                    mysql_query($sql);
                }
            }
        }
    }
}

function import_sheet($con, $sheet_name, $mapping, $spreadsheet) {
    global $category;
    global $blogger_info;

    $current_row = 0;
    $blogger_id = 0;

    $show = false;

    foreach ($spreadsheet as $key => $value) {

        if (preg_match('/([A-Z]+)(\d+)/', $key, $key_string)) {
            $col = $key_string[1];
            $row = $key_string[2];

            if ($row > 1) {
                // create new blogger
                if ($row > $current_row) {
                    $sql = "insert into fs_blogger (Source) values ('" . $sheet_name . "') ";
                    if ($show) echo $sql . "\n";
                    mysql_query($sql);
                    $blogger_id = mysql_insert_id();
                    $current_row = $row;
                }
                if ($show) echo "column: $col name:  " . $mapping[$col] . "\n";
                if (in_array($mapping[$col], $category)) {
                    $name = $mapping[$col];
                    $sql = "select id from fs_blogger_cat_map where blogger_id = '$blogger_id' 
                              and category_id = (select id from fs_blogger_category where name = '$name')";
                    if ($show) echo $sql . "\n";
                    $res = mysql_query($sql);
                    if (mysql_num_rows($res) <= 0) {
                        $sql = "insert into fs_blogger_cat_map (blogger_id, category_id) values ('$blogger_id', (select id from fs_blogger_category where name = '$name'))";
                        if ($show) echo $sql . "\n";
                        mysql_query($sql);
                    }
                }
                else if (isset($mapping[$col])) {
                    $col_name = str_replace('?', '', $mapping[$col]);
                    if ($mapping[$col] == 'Blog') 
                        $col_name = "Blog_Name";
                    if ($mapping[$col] == 'Name') 
                        $col_name = "First_Name";
                    if ($mapping[$col] == 'First_name') 
                        $col_name = "First_Name";
                    if ($mapping[$col] == 'FIRST_NAME') 
                        $col_name = "First_Name";
                    if ($mapping[$col] == 'Last_name') 
                        $col_name = "Last_Name";
                    if ($mapping[$col] == 'EMAIL_ADDRESS') 
                        $col_name = "Email";
                    if ($mapping[$col] == 'TWITTER') 
                        $col_name = "Twitter";
                    if ($mapping[$col] == 'Blog(s)') 
                        $col_name = "Blog_Name";
                    if (!in_array($col_name, $blogger_info) ) {
                        $note = $col_name . "-> " . addslashes($value) . "\n";
                        $sql = "update fs_blogger set import_notes = concat(import_notes, '" . $note . "') where id = " . $blogger_id;
                        if ($show) echo  $sql;
                        mysql_query($sql);
                        echo $note;
                    }
                    $sql = "update fs_blogger set " . $col_name . " = '" . addslashes($value) . "' where id = " . $blogger_id;
                    if ($show) echo $sql . "\n";
                    mysql_query($sql);
                }
                else {
                    $note = $key . "-> " . addslashes($value) . "\n";
                    $sql = "update fs_blogger set import_notes = concat(import_notes, '" . $note . "') where id = " . $blogger_id;
                    echo "Can't map this field: " . $note;
                }
            }
        }
    }
}

FUNCTION create_grid($con) {
    $spreadsheets = unserialize(file_get_contents("Blogger0_worksheet.txt"));

    $current_row = 0;
    $blogger_id = 0;
    foreach ($spreadsheets['Sheet1'] as $key => $value) {

        if (preg_match('/([A-Z]+)(\d+)/', $key, $key_string)) {
            $col = $key_string[1];
            $row = $key_string[2];

            if ($row > 1) {
                // create new blogger
                if ($row > $current_row) {
                    $sql = "insert into fs_blogger (Source) values('1') ";
                    //                echo $sql . "\n";
                    mysql_query($sql);
                    $blogger_id = mysql_insert_id();
                    $current_row = $row;
                }

                if (isset($category[$col])) {
                    $name = $category[$col];
                    $sql = "select id from fs_blogger_cat_map where blogger_id = '$blogger_id' and category_id = '$category_id'";
                    $res = mysql_query($sql);
                    if (mysql_num_rows($res) <= 0) {
                        $sql = "insert into fs_blogger_cat_map (blogger_id, category_id) values ('$blogger_id', (select id from fs_blogger_category where name = '$name'))";
                        mysql_query($sql);
                    }
                }
                else if (isset($blogger_info[$col])) {
                    $col_name = $blogger_info[$col];
                    $sql = "update fs_blogger set " . $col_name . " = '" . $value . "' where id = " . $blogger_id;
                    //                echo $sql . "\n";
                    mysql_query($sql);
                }
            }
        }
    }

}

function create_category($category, $con) {

$sql = "CREATE TABLE fs_blogger_category (
id int NOT NULL AUTO_INCREMENT, 
PRIMARY KEY(id),
cat_id varchar(8),
name varchar(256)
)";

    // Execute query
    $res =  mysql_query($sql,$con);
    if  (!$res) echo mysql_error();
}

function populate_categories($category, $con) {

    foreach ($category as $key => $name) {
        $name = str_replace(' ', '_', $name);
        $sql = "insert into fs_blogger_category (cat_id, name) values ('$key', '$name')";
        echo $sql . "\n";
        // Execute query
        $res =  mysql_query($sql, $con);
        if  (!$res) echo mysql_error();
    }
}

function create_fs_blogger($con) {

$sql = "CREATE TABLE fs_blogger (
id int NOT NULL AUTO_INCREMENT, 
PRIMARY KEY(id),
od6  int,
Source varchar(256),
City varchar(128), 
`wState varchar(128), 
Country varchar(128), 
Blog_Name varchar(128), 
URL varchar(128),
Title varchar(128),
First_Name  varchar(128),
Last_Name varchar(128),
Email  varchar(128),
Email_2  varchar(128),
Web_Form_URL  varchar(128),
Phone  varchar(128),
Skype  varchar(128),
Fax  varchar(128),
Authority int,
Twitter_Blogger  varchar(128),
Twitter_Blogger_Followers  varchar(128),
Twitter_Outlet  varchar(128),
Twitter_Outlet_Followers  varchar(128),
Facebook  varchar(128),
Fans  int, 
Notes varchar(256),
Estimated_Readership int,
Media_Outlet varchar(256),
Additional_Contacts varchar(256),
Responded_to_7_29_SB1070_pitch int,
Posted_re_SB1070 int
)";

    // Execute query
    $res =  mysql_query($sql,$con);
    if  (!$res) echo mysql_error();
}

?>