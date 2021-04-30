<?php
require_once 'php/DBConnect.php';
$db = new DBConnect();

$title = "Frequently Asked Questions";
$setFAQActive = "active";
include 'layout/_header.php';
include 'layout/_navbar.php';
?>
<div class="container my-3">
    <div class="row justify-content-center">
        <h1 class="my-4" id="heading">Frequently Asked Questions</h1>
        
        <div class="col-sm-10 mt-2">
            <div class="accordion radius border" id="FAQ_Section">
            
                <div class="card">
                    <div class="card-header bg-white" id="Q1">
                        <h5>
                            <button class="btn text-left text-decoration-none" type="button" data-toggle="collapse" data-target="#Q1Collapse" aria-expanded="false" aria-controls="Q1Collapse" style="color: #000407">
                            Who can donate blood?
                            </button>
                        </h5>
                    </div>
                    <div id="Q1Collapse" class="collapse" aria-labelledby="Q1" data-parent="#FAQ_Section">
                        <div class="card-body bg-white">
                            Any healthy person with clean habits between the age of 18 to 65 can donate blood.
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header bg-light" id="Q2">
                        <h5>
                            <button class="btn text-left text-decoration-none" type="button" data-toggle="collapse" data-target="#Q2Collapse" aria-expanded="false" aria-controls="Q2Collapse" style="color: #000407">
                            How long will it take to replenish the blood I donate?
                            </button>
                        </h5>
                    </div>
                    <div id="Q2Collapse" class="collapse" aria-labelledby="Q2" data-parent="#FAQ_Section">
                        <div class="card-body bg-light">
                            The plasma from your donation is replaced within about 24 hours. But red blood cells need about four to six weeks for complete replacement.
                            That is why at least eight weeks (56 days) are required between whole blood donations.
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header bg-white" id="Q3">
                        <h5>
                            <button class="btn text-left text-decoration-none" type="button" data-toggle="collapse" data-target="#Q3Collapse" aria-expanded="false" aria-controls="Q3Collapse" style="color: #000407">
                            Whom do I contact in case of blood requirement?
                            </button>
                        </h5>
                    </div>
                    <div id="Q3Collapse" class="collapse" aria-labelledby="Q3" data-parent="#FAQ_Section">
                        <div class="card-body bg-white">
                            You can contact the nearest blood bank located in the Government Medical College Hospitals, District Head Quarters Hospitals and other Government hospitals.
                            To find their contact details and location, you can search <a href="/www/BloodBank/branch.php">here</a>.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'layout/_footer.php'; ?>