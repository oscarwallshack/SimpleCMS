function myFunction() {
  const x = document.getElementById("id_top_nav");
  if (x.className === "top_nav") {
    x.className += " responsive";
  } else {
    x.className = "top_nav";
  }
}


  const btn_new_page = document.querySelector(".btn_new_page");
  const form_new_page = document.querySelector(".div_display");

  btn_new_page.addEventListener('click', () => {
    form_new_page.classList.toggle("form_hide");
    });
