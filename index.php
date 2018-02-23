<?php session_start(); 
$passagem_final = $_SESSION['PERMISSAO_FINAL'];
?>

<!-- CONEXAO -->
  <?php
    date_default_timezone_set('UTC');
    $file = parse_ini_file("../../../../fiveam.ini");

    $host = trim($file["dbhost"]);
    $user = trim($file["dbuser"]);
    $pass = trim($file["bdpass"]);
    $name = trim($file["dbname"]);

    //Include access.php to call func from access.php file
    require ("../serverside/secure/access.php");
    $access = new access($host, $user, $pass, $name);
    $access->connect();
  ?>
<!-- CONEXAO -->

<!doctype html>
<html lang="en">
<head>

  <?php include 'templates/header.php'; ?>

</head>
<body class="home">

<div id="app">


  <div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
    
    <?php include 'templates/nav.php'; ?>

    <main class="mdl-layout__content mat-typography">
      

      <!-- Modal Compose -->
        <?php include 'templates/widget_post.php'; ?>
      <!-- Modal Compose -->


      <!-- Modal Login -->
        <?php include 'templates/widget_login.php'; ?>
      <!-- Modal Login -->

     
      <div class="page-content">
        
        <div class="checkin_button">
          <img src="src/images/logo.png" alt="5Club">
          <h1>Fazer check-in das 5:00</h1>
        </div>

        <div id="shared-moments"></div>

      </div>


      <!-- Verifica se ta logado para poder postar -->
      <?php
        if(!empty($_SESSION['id'])){ ?>
          <div class="floating-button">
            <button class="mdl-button mdl-js-button mdl-button--fab mdl-button--colored" id="share-image-button">
              <i class="material-icons">add</i>
            </button>
          </div>
      <?php } else {
        ?>
          <div class="floating-button">
            <button class="mdl-button mdl-js-button mdl-button--fab mdl-button--colored" id="precisa-logar">
              <i class="material-icons">add</i>
            </button>
          </div>
        <?php
      }
      ?>
      <!-- Verifica se ta logado para poder postar -->

      <div id="confirmation-toast" aria-live="assertive" aria-atomic="true" aria-relevant="text" class="mdl-snackbar mdl-js-snackbar">
        <div class="mdl-snackbar__text"></div>
        <button type="button" class="mdl-snackbar__action"></button>
      </div>

    </main>
  </div>
</div>


<?php include 'templates/footer.php'; ?>

<?php $access->disconnect(); ?>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <script type="text/javascript">
        mixpanel.identify(<?php echo $_SESSION['id'] ?>);
        mixpanel.people.set({
            "$first_name": "<?php echo $_SESSION['first_name'] ?>",
            "$last_name": "<?php echo $_SESSION['last_name'] ?>",
            "$email": "<?php echo $_SESSION['email'] ?>",
            "$avatar": "<?php echo $_SESSION['avatar'] ?>",
            "$gender": "<?php echo $_SESSION['gender'] ?>",
            "$locale": "<?php echo $_SESSION['locale'] ?>",
            "$link": "<?php echo $_SESSION['link'] ?>",
        });
    </script>
  
    <script type="text/javascript">
      jQuery(document).ready(function($) {

        $('.login_necessario').click(function(){    
          openLoginModal();
        });

      });
    </script>

</body>
</html>