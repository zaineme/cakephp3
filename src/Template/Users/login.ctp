<center>
	<div class="large-3 medium-4">
		<h1>Login</h1>
		<?= $this->Form->create() ?>
		<?= $this->Form->control('email') ?>
		<?= $this->Form->control('password') ?>
		<?= $this->Form->button('Login') ?>
		<?= $this->Form->end() ?>
		or
		<div class="g-signin2" data-onsuccess="onSignIn"></div>
	</div>
</center>

<meta name="google-signin-client_id" content=
"995177107312-q9n1blce5b2uqb2u570fj0vq4ao0f6vc.apps.googleusercontent.com">
<script src="https://apis.google.com/js/platform.js" async defer></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript">
	let csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;
	function onSignIn(googleUser) {
		let defaultUrl = window.location.origin+"/nixser/articles";
		var profile = googleUser.getBasicProfile();
		$.ajax({
			type: 'POST',
			headers: {
				'X-CSRF-Token': csrfToken
			},
			data: {
				email: profile.getEmail(),
			},
			success: function(response) {
				if (response.success) {
					window.location.replace(defaultUrl);
				}
			}
		})
	}
</script>