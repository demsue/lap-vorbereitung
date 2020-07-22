<?php
session_start();
require_once('../modules/config.php');
require_once('../classes/user.php');

// creates a new empty user Object
$user = new User();
/*
unsearialies the previous serialised user 
and saves it as the newly created user
*/
$user = unserialize($_SESSION['user']);
// Gets an array of all user for further processing 
$allUser = $database->getAllUser();
// Gets an array of all internal user for further processing 
$allInternalUser = $database->getAllInternalUser();
// Gets the Course the Trainer is responsible for
$course = $database->getCourse($user->courseId);
// Gets all Documents for the Course
$documents = $database->getDocuments($course->courseId);
// Determins if the User in the session storage is set and an admin
if ($user) {
    /*
    Asks the user to login if the userSite.php got accesed via the searchbar
    and no User is in the session storage
    */
     if (@$user->userRole === 0) {
        die('als admin <a href="logout.php">einloggen</a>');
    }

} else {
  // Dies if the user in the session storage is not set
    die('Bitte zuerst <a href="logout.php">einloggen</a>');
}
?>

<!DOCTYPE html>
<html lang="en">
<title>Website</title>
<body>
    <!-- Navbar -->
    <div id="navbar">
        <ul style="display: flex;">
            <a href="../index.html" class="bar-item button padding-large white">Home</a>
            <div style="flex-grow: 1;"></div>
            <a href="./logout.php" class="bar-item button padding-large white">Logout</a>
    </div>
    <!-- Content -->
    <!-- Div that centers the displayed register form -->
    <div style=" display: block;margin-left: auto;margin-right: auto; margin-top: 2rem;width: max-content;">

        <h1>Guten Tag, <?php echo $user->firstname, ' ', $user->lastname ?> </h1>
        <h2>Ihre Dokumente für den Kurs <?php echo $course->name ?> </h2>
        <table style="width:100%">
            <tr style="text-align: left;">
                <th>Dateiname</th>
                <th>Datei</th>
            </tr>
            <?php
            // creates one entry in the html for each object in the courses array
            foreach ($documents as &$document) {
            ?>
            <tr>
                <td><?php echo $document->displayName ?></td>
                <td><a href="../<?php echo $document->path ?>">download</a></td>
            </tr>
            <?php
            }
            ?>
        </table>
        <h3> Alle Benutzer in meinem Kurs</h3>
        <table style="width:100%">
            <tr style="text-align: left;">
                <th>Vorname</th>
                <th>Nachname</th>
                <th>EMail</th>
                <th>Kurs</th>
            </tr>
            <?php
            // creates one entry in the html for each object in the allUser array
            foreach ($allUser as $sUser) {
				$course = $database->getCourse($sUser->courseId);
				if($sUser->courseId == $user->courseId)
				{
            ?>
            <tr>
                <td><?php echo $sUser->firstname ?></td>
                <td><?php echo $sUser->lastname ?></td>
                <td><?php echo $sUser->email ?></td>
                <td><?php echo $course->name ?></td>
            </tr>
            <?php
				}
            }
            ?>
        </table>
    </div>
</body>

</html>