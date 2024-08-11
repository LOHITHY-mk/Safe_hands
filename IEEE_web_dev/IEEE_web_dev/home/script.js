const nav = document.querySelector('.nav-el')
fetch('./nav.html' )
.then(res=>res.text())
.then(data=>{
    nav.innerHTML=data
})
 