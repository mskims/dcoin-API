<form action="/auth/login_process" method="POST">
	<input type="hidden" name="csrf_token" value="<?=$this->csrf_token?>">
	<input type="hidden" name="redirect_url" value="<?=$this->redirect_url?>">
	<div class="form-group">
		디지텍코인 로그인으로 <strong><?=$this->app_info["name"]?></strong> 서비스를 이용하실 수 있습니다.
	</div>
	<div class="form-group">
		<input type="text" name="account_number" placeholder="아이디" class="form-control" required="">
	</div>
	<div class="form-group">
		<input type="password" name="pw" placeholder="비밀번호" class="form-control" required="">
	</div>
	<?php if(!empty($this->errors)){ ?>
	<div class="form-group errors">
		 <?php foreach($this->errors as $error){ echo $error."<br>"; } ?>
	</div>
	<?php } ?>
	<button class="btn btn-info">로그인</button>
</form>