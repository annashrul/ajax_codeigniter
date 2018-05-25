<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?=$title?></title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    
   
  </head>
  <style type="text/css">
    button.btn-primary, button.btn-info, button.btn-danger, button.btn-default, button.btn-dark, button.btn-success, button.btn-warning{border-radius:0px;}
    a.btn-primary, a.btn-info, a.btn-danger, a.btn-default, a.btn-dark, a.btn-success, a.btn-warning{border-radius:0px;}
    .alert{border-radius:0px;}
    .wrap{margin-top:50px;}
    a.btn-primary{margin-bottom:10px;}
    a.btn-info{margin-right:5px;}
  </style>
<div class="col-md-8 col-md-offset-2 wrap">
  <a href="#form" class="btn btn-primary" data-toggle="modal" onclick="submit('tambah')"><i class="fa fa-plus"></i> tambah</a>
  
  <table id="datatable" class="table table-striped table-bordered">
    <thead>
      <tr>
        <th>No</th>
        <th>Nama</th>
        <th>Email</th>
        <th>Pesan</th>
        <th>aksi</th>
      </tr>
    </thead>
    <tbody id="data_kontak"></tbody>
  </table>
</div>
<div class="modal fade" id="form" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="text-center"><?=$title?></h3>
      </div>
      <div class="modal-body">
        <p class="text-center" id="pesan" style="color:red!important;font-weight:600;"></p>
        <div class="form-group">
          <label>Nama</label>
          <input type="text" name="nama_kontak" id="nama_kontak" class="form-control">
          <input type="hidden" name="id_kontak" id="id_kontak" value="">
        </div>
        <div class="form-group">
          <label>Email</label>
          <input type="email" name="email_kontak" id="email_kontak" class="form-control">
        </div>
        <div class="form-group">
          <label>Pesan</label>
          <textarea type="text" name="pesan_kontak" id="pesan_kontak" class="form-control"></textarea>
        </div>
        <div class="form-group">
          <button type="button" class="btn btn-primary" name="btn-tambah" id="btn-tambah" onclick="tambahData()">Tambah</button>
          <button type="button" class="btn btn-primary" name="btn-edit" id="btn-edit" onclick="editData()">Edit</button>
          <button type="reset" data-dismiss="modal" class="btn btn-default">Batal</button>
        </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript" charset="utf-8" async defer>
  ambil_data();
  function submit(param){
    if(param == 'tambah'){
      $("#btn-tambah").show();
      $("#btn-edit").hide();
    }else{
      $("#btn-edit").show();
      $("#btn-tambah").hide();

      $.ajax({
        url:"<?=base_url('kontak/ambil_id')?>",
        dataType:"JSON",
        type:"POST",
        data:"id_kontak="+param,
        success:function(hasil){
          if(param != 'tambah'){
            $("#id_kontak").val(hasil[0].id_kontak);
            $("#nama_kontak").val(hasil[0].nama_kontak);
            $("#email_kontak").val(hasil[0].email_kontak);
            $("#pesan_kontak").val(hasil[0].pesan_kontak);
          }else if(param == ''){
            $("#nama_kontak").val(hasil[0].nama_kontak);
            $("#email_kontak").val(hasil[0].email_kontak);
            $("#pesan_kontak").val(hasil[0].pesan_kontak);
          }
        }
      });
    }
  }
  function ambil_data(){
    $.ajax({
      url:"<?=base_url('kontak/ambil_data')?>",
      type:"POST",
      dataType:"JSON",
      success:function(data){
        var tampung = "";
        var i = 0;
        var no = 1;
        for(i=0;i<data.length;i++){
          tampung +=
          "<tr>"+
            "<td>"+no+"</td>"+
            "<td>"+data[i].nama_kontak+"</td>"+
            "<td>"+data[i].email_kontak+"</td>"+
            "<td>"+data[i].pesan_kontak+"</td>"+
            "<td>"+
              "<a href='#form' data-toggle='modal' class='btn btn-info' onclick='submit("+data[i].id_kontak+")'>Edit</a>"+
              "<a class='btn btn-danger' onclick='hapus("+data[i].id_kontak+")'>Hapus</a>"+
            "</td>"+
          "</tr>";
          no++;
        }
        $("#data_kontak").html(tampung);
      }
    });
  }

  function tambahData(){
    var kontak = {
      "nama_kontak"  : $("#nama_kontak").val(),
      "email_kontak" : $("#email_kontak").val(),
      "pesan_kontak" : $("#pesan_kontak").val(),
    }
    $.ajax({
      url:"<?=base_url('kontak/tambah_data')?>",
      type:"POST",
      dataType:"JSON",
      data:kontak,
      success:function(hasil){
        $("#pesan").html(hasil.pesan);
        if(hasil.pesan == ""){
          $("#form").modal('hide');
          alert('data berhasil diinput');
          $("#nama_kontak").val("");
          $("#email_kontak").val("");
          $("#pesan_kontak").val("");
          // location.reload();
          ambil_data();
        }
      }
    });
  }

  function editData(){
    var kontak = {
      "id_kontak"  : $("#id_kontak").val(),
      "nama_kontak"  : $("#nama_kontak").val(),
      "email_kontak" : $("#email_kontak").val(),
      "pesan_kontak" : $("#pesan_kontak").val(),
    }
    $.ajax({
      url:"<?=base_url('kontak/edit_data')?>",
      type:"POST",
      dataType:"JSON",
      data:kontak,
      success:function(hasil){
        $("#pesan").html(hasil.pesan);
        if(hasil.pesan == ""){
          $("#form").modal('hide');
          alert('data berhasil diupdate');
          $("#id_kontak").val("");
          $("#nama_kontak").val("");
          $("#email_kontak").val("");
          $("#pesan_kontak").val("");
          // location.reload();
          ambil_data();
        }
      }
    });
  }

  function hapus(id_kontak){
    var tanya = confirm('anda yakin akan menghapus data ini ??');
    if(tanya){
      $.ajax({
        url:"<?=base_url('kontak/hapus')?>",
        data:"id_kontak="+id_kontak,
        dataType:"JSON",
        type:"POST",
        success:function(){
          // location.reload();
          ambil_data();
        }
      });
    }
  }
</script>

<!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
