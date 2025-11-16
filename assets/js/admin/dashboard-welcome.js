(function () {
	// Toggle light/dark mode on the admin body via class (default dark)
	const button = document.querySelector('.alqasr-welcome__mode-toggle');
	if (!button) return;

	const body = document.body;
	const STORAGE_KEY = 'alqasr_admin_theme';

	// Respect previously saved preference
	const savedMode = localStorage.getItem(STORAGE_KEY);
	if (savedMode === 'light') {
		body.classList.add('is-light');
	}

	button.addEventListener('click', function () {
		const isLight = body.classList.toggle('is-light');
		localStorage.setItem(STORAGE_KEY, isLight ? 'light' : 'dark');
	});
})();


