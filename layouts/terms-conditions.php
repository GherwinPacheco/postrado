<?php
    $terms = $conn->query("SELECT * FROM terms_conditions WHERE 1");
?>

<!-- Terms and Conditions Modal -->
<div class="modal fade" id="termsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      
      <div class="modal-body p-5">
        <h2 class="text-center text-dbrown">POSTRADO</h2>
        <h5 class="text-center text-medium mb-4">Terms and Conditions</h5>
        
        <p class="text-justify mb-5 text-muted" style="text-indent: 30px"><?=$config["terms_heading"]?></p>

        <ol class="p-0 pl-2">
            <?php
                while($row = $terms->fetch_assoc()){
                    echo '
                        <li class="text-justify mb-2">
                            <b class="text-medium">'.$row["title"].':</b>
                            <span class="text-muted">'.$row["content"].'</span>
                        </li>
                    ';
                }
            ?>
        </ol>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>