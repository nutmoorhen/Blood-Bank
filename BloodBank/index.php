<?php
require_once 'php/DBConnect.php';
$db = new DBConnect();

$title = "Home";
$setHomeActive = "active";
include 'layout/_header.php';
include 'layout/_navbar.php';
?>
<div class="container my-3">
    <div class="row">
        <div class="col-sm-8">
            <h5>Our Purpose</h5>
            <ul name="About">
                <li>Blood banks collect blood and separate it into its various components so they can be used most effectively according to the needs of the patient.</li>
                <li>Our Country's blood system is dependent on donations, and new donors for blood are always needed</li>
                <li>We ensure that the blood collection process takes place systematically for smooth operation</li>
                <li>Our storage facilities include refrigerated and low temperature environment for preservation of donated blood</li>
                <li>We allow multiple blood bank registration so donor can always donated blood to one of our registered blood banks</li>
                <li>We maintain our integrity by providing good services</li>
            </ul>
        </div>
        <div class="col-sm-4">
            <label class="col-sm-12 mb-4"> </label>
            <img src="/www/BloodBank/assets/Blood.jpg" class="img img-responsive img-thumbnail ml-4" width="210" height="128">
        </div>
    </div>
</div>

<?php include 'layout/_footer.php'; ?>