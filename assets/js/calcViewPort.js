var vh = window.innerHeight * 0.01,
    vw = window.innerWidth * 0.01;
		
window.addEventListener('resize', () => {
	document.documentElement.style.setProperty('--vh', vh+"px");
	document.documentElement.style.setProperty('--vw', vw+"px");
});