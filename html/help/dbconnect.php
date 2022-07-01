<?PHP
/****
CREATE USER 'doc_user'@'localhost' IDENTIFIED BY '13579';
grant select, update, delete, on intranet.* to 'doc_user'@'localhost';
flush privileges;
****/
session_start();
$configVars['MYSQL']['HOST'] = 'localhost';
$configVars['MYSQL']['USER'] = 'doc_user';
$configVars['MYSQL']['PASSWORD'] = '13579';

$connect = @mysqli_connect($configVars['MYSQL']['HOST'], $configVars['MYSQL']['USER'], $configVars['MYSQL']['PASSWORD'], 'document');

if(!$connect) {
	echo "DB  Select Error";
	exit;
}

?>