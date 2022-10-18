<!-- start Simple Custom CSS and JS -->
<script type="text/javascript">
if (window.innerWidth > 1024) { // code for large screens 
  window.onload = function() {  	
      var navstatus1 = localStorage.getItem('state1', '');
      localStorage.setItem('state1', navstatus1);
      if (navstatus1 == 'collapsed') {
        document.getElementById("elementor-popup-modal-94").style.setProperty("display", "none", "important");
        document.getElementById("mynav-main").style.marginLeft = "65px";
        document.getElementById("myfooter-main").style.marginLeft = "65px";
      }
      else {
       document.getElementById("elementor-popup-modal-91").style.setProperty("display", "none", "important");
       document.getElementById("myfooter-main").style.marginLeft = "300px"; 
      }
  }
} else {
	document.getElementById("mynav-main").style.marginLeft = "0";
}</script>
<!-- end Simple Custom CSS and JS -->
