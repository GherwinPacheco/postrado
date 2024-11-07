<?php if(isset($_SESSION["message"]) and $_SESSION["message"] !== ''){ ?>
    <script type="text/javascript">
        alertMessage("<?=$_SESSION["message"]?>", "<?=$_SESSION["res"]?>")
    </script>
<?php } 
    $_SESSION["message"] = '';
    $_SESSION["res"] = '';
?>