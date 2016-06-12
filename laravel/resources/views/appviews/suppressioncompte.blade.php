@extends('layouts.app')

@section('content')
    <div class="container" style="width:100%;background-color:#FAFAFA">
    <div class="row">
      <div class="col s12" style="padding-left:25px;padding-right:20px;">
       <h4>Suppression de votre compte client</h4>
       <p>La suppression de votre compte client entraine la suppression dans nos fichiers de :</p>
       <p>- Vos commandes</p>
       <p>- Vos informations personelles</p>
       <p>- Du compte sur le site </p>
       <h5>Êtes-vous vraiment sur de vouloir supprimer votre compte ? <br>Cette action est irréversible</h5><br>
       <a href="{{action('ClientController@supprClient')}}">
         <button type="submit" class="waves-effect waves-light btn-large" onclick="Materialize.toast('Suppression du compte..', 4000,'')">Supprimer mon compte</button></a>
       <div class="divider" style="margin-bottom:10px;"></div>
      </div>
      <div class="col s6" style="padding-left:25px;padding-right:20px;">
      </div>
      <div class="col s6"></div>
      </div>
      <div class="col s12" style="text-align: center;background-color: #EAEAEA;position: relative;bottom: 0;width: 100%;"><p color="#565F76;">PRESSING DES HALLES - 17 Rue verchant  - 04 67 90 67 21</p></div>
      </div>
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
      <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.3/Chart.min.js"></script>
      <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.3/Chart.bundle.min.js"></script>
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
      <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.3/Chart.min.js"></script>
      <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.3/Chart.bundle.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/pickadate.js/3.5.6/picker.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<style>

input {
        background-color: #F5F5F5!important;
    border-radius: 5px!important;
    border: 1px solid #363C44!important;
    color: #363C44!important;
    padding-left: 5px!important;
    width: 89.5%!important;
}
<script>
 $(document).ready(function() {
    $('select').material_select();
  });
</script>
    </body>
</html>
@endsection
