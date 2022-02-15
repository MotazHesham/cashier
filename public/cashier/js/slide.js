// $(document).ready(function(){
//     $(".owl-carousel").owlCarousel();
// });

$('.owl-carousel').owlCarousel({
    loop:true,
    margin:10,
    nav:true,
    responsive:{
        0:{
            items:1
        },
        600:{
            items:3
        },
        1000:{
            items:5
        }
        
    }
})

document.addEventListener("DOMContentLoaded", function(event) {

    const showNavbar = (toggleId, navId, bodyId, headerId) =>{
    const toggle = document.getElementById(toggleId),
    nav = document.getElementById(navId),
    bodypd = document.getElementById(bodyId),
    headerpd = document.getElementById(headerId)

    if(toggle && nav && bodypd && headerpd){
    toggle.addEventListener('click', ()=>{
    nav.classList.toggle('navbar-show')
    toggle.classList.toggle('bx-x')
    bodypd.classList.toggle('body-pd')
    headerpd.classList.toggle('body-pd')
    })
    }
    }

    showNavbar('header-toggle','nav-bar','body-pd','header')

    const linkColor = document.querySelectorAll('.nav_link')

    function colorLink(){
    if(linkColor){
    linkColor.forEach(l=> l.classList.remove('active'))
    this.classList.add('active')
    }
    }
    linkColor.forEach(l=> l.addEventListener('click', colorLink))

    });


    var buttons = document.querySelectorAll('.button')
buttons.forEach(function (button) {
  var button = new bootstrap.Button(button)
  button.toggle()
})


$(document).ready(function(){ 
    $(document).on('click','.plus',function(){
        var id = $(this).data('id'); 
        $('#quantity-'+id).val(parseInt($('#quantity-'+id).val()) + 1 );
    });

    $(document).on('click','.minus',function(){
        var id = $(this).data('id'); 
        $('#quantity-'+id).val(parseInt($('#quantity-'+id).val()) - 1 );
        if ($('#quantity-'+id).val() == 0) {
            $('#quantity-'+id).val(1);
        }
    });
});