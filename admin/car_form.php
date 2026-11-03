<?php
require_once dirname(__DIR__).'/config.php'; require_once dirname(__DIR__).'/includes/functions.php'; require_admin();
$id=filter_input(INPUT_GET,'id',FILTER_VALIDATE_INT) ?: 0;
$car=['brand'=>'','model'=>'','price'=>'','year'=>'','transmission'=>'Automatic','fuel'=>'Bensin','seats'=>5,'mileage'=>0,'color'=>'','description'=>'','status'=>'Tersedia','image'=>null];
if($id){$stmt=$pdo->prepare('SELECT * FROM cars WHERE id=?');$stmt->execute([$id]);$found=$stmt->fetch();if(!$found){http_response_code(404);exit('Mobil tidak ditemukan');}$car=$found;}
$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 csrf_check();
 foreach(['brand','model','price','year','transmission','fuel','seats','mileage','color','description','status'] as $key){$car[$key]=trim((string)($_POST[$key]??''));}
 if($car['brand']===''||$car['model']==='')$error='Merek dan model wajib diisi.';
 elseif(!ctype_digit((string)$car['price']) || (int)$car['price']<0)$error='Harga harus berupa angka tanpa titik.';
 elseif($car['year']!=='' && (!ctype_digit((string)$car['year']) || (int)$car['year']<1950 || (int)$car['year']>date('Y')+1))$error='Tahun tidak valid.';
 elseif(!in_array($car['transmission'],['Automatic','Manual','CVT','Lainnya'],true))$error='Transmisi tidak valid.';
 elseif(!in_array($car['fuel'],['Bensin','Diesel','Hybrid','Listrik','Lainnya'],true))$error='Bahan bakar tidak valid.';
 elseif(!in_array($car['status'],['Tersedia','Terjual','Disembunyikan'],true))$error='Status tidak valid.';
 else{
  try{
   $newImage=upload_car_image($_FILES['image']??['error'=>UPLOAD_ERR_NO_FILE]);
   $image=$newImage ?: ($car['image']??null);
   $slug=make_slug($car['brand'].'-'.$car['model']);
   if($id){$stmt=$pdo->prepare('UPDATE cars SET brand=?,model=?,slug=?,price=?,year=?,transmission=?,fuel=?,seats=?,mileage=?,color=?,description=?,status=?,image=? WHERE id=?');$stmt->execute([$car['brand'],$car['model'],$slug,(int)$car['price'],$car['year']!==''?(int)$car['year']:null,$car['transmission'],$car['fuel'],max(1,min(20,(int)$car['seats'])),max(0,(int)$car['mileage']),$car['color'],$car['description'],$car['status'],$image,$id]);if($newImage)delete_car_image($found['image']);}
   else{$stmt=$pdo->prepare('INSERT INTO cars(brand,model,slug,price,year,transmission,fuel,seats,mileage,color,description,status,image) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?)');$stmt->execute([$car['brand'],$car['model'],$slug,(int)$car['price'],$car['year']!==''?(int)$car['year']:null,$car['transmission'],$car['fuel'],max(1,min(20,(int)$car['seats'])),max(0,(int)$car['mileage']),$car['color'],$car['description'],$car['status'],$image]);}
   $_SESSION['flash']='Data mobil berhasil disimpan.';header('Location: '.BASE_URL.'admin/cars.php');exit;
  }catch(Throwable $ex){$error=$ex instanceof RuntimeException?$ex->getMessage():'Gagal menyimpan data. Periksa data dan database.';}
 }
}
$pageTitle=$id?'Edit Mobil':'Tambah Mobil';require dirname(__DIR__).'/includes/header.php';
?>
<div class="admin-nav py-4 text-white"><div class="container d-flex justify-content-between align-items-center"><h1 class="h3 mb-0"><?= e($pageTitle) ?></h1><a href="<?= BASE_URL ?>admin/cars.php" class="btn btn-outline-light">Kembali</a></div></div><main class="container py-4" style="max-width:900px"><div class="card admin-card p-3 p-md-4"><?php if($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?><form method="post" enctype="multipart/form-data"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><div class="row g-3">
<div class="col-md-6"><label class="form-label">Merek *</label><input name="brand" class="form-control" required value="<?= e($car['brand']) ?>" placeholder="Toyota"></div><div class="col-md-6"><label class="form-label">Model *</label><input name="model" class="form-control" required value="<?= e($car['model']) ?>" placeholder="Avanza"></div>
<div class="col-md-6"><label class="form-label">Harga (angka tanpa titik)</label><input name="price" type="number" min="0" class="form-control" required value="<?= e($car['price']) ?>" placeholder="185000000"></div><div class="col-md-6"><label class="form-label">Tahun</label><input name="year" type="number" min="1950" max="<?= date('Y')+1 ?>" class="form-control" value="<?= e($car['year']) ?>"></div>
<div class="col-md-4"><label class="form-label">Transmisi</label><select name="transmission" class="form-select"><?php foreach(['Automatic','Manual','CVT','Lainnya'] as $v): ?><option <?= $car['transmission']===$v?'selected':'' ?>><?= e($v) ?></option><?php endforeach; ?></select></div><div class="col-md-4"><label class="form-label">Bahan bakar</label><select name="fuel" class="form-select"><?php foreach(['Bensin','Diesel','Hybrid','Listrik','Lainnya'] as $v): ?><option <?= $car['fuel']===$v?'selected':'' ?>><?= e($v) ?></option><?php endforeach; ?></select></div><div class="col-md-4"><label class="form-label">Kapasitas penumpang</label><input name="seats" type="number" min="1" max="20" class="form-control" value="<?= e($car['seats']) ?>"></div>
<div class="col-md-6"><label class="form-label">Kilometer</label><input name="mileage" type="number" min="0" class="form-control" value="<?= e($car['mileage']) ?>"></div><div class="col-md-6"><label class="form-label">Warna</label><input name="color" class="form-control" value="<?= e($car['color']) ?>"></div>
<div class="col-md-6"><label class="form-label">Status</label><select name="status" class="form-select"><?php foreach(['Tersedia','Terjual','Disembunyikan'] as $v): ?><option <?= $car['status']===$v?'selected':'' ?>><?= e($v) ?></option><?php endforeach; ?></select></div><div class="col-md-6"><label class="form-label">Foto mobil (JPG/PNG/WEBP, maks. 5 MB)</label><input type="file" name="image" class="form-control" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"><?php if($car['image']): ?><div class="small text-secondary mt-1">Foto saat ini: <?= e($car['image']) ?></div><?php endif; ?></div>
<div class="col-12"><label class="form-label">Deskripsi</label><textarea name="description" rows="4" class="form-control"><?= e($car['description']) ?></textarea></div><div class="col-12"><button class="btn btn-primary px-4">Simpan Mobil</button> <a href="<?= BASE_URL ?>admin/cars.php" class="btn btn-outline-secondary">Batal</a></div>
</div></form></div></main><?php require dirname(__DIR__).'/includes/footer.php'; ?>
