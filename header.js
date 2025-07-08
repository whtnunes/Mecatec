
let navBar = document.querySelector('#header')

document.addEventListener("scroll", ()=>{
    let scrollTop = window.scrollY

    if(scrollTop > 0){
        navBar.classList.add('rolar')
    }
    else{
        navBar.classList.remove('rolar')
    }
})

function alternarImagem() {
    // Seleciona a imagem
    var imagem = document.getElementById("minhaImagem");

    // Verifica o estado atual de visibilidade e alterna
    if (imagem.style.display === "none") {
        imagem.style.display = "block";  // Exibe a imagem
    } else {
        imagem.style.display = "none";   // Oculta a imagem
    }
}


 function abrirImagem() {
        document.getElementById("overlay").style.display = "flex"; // Exibe o overlay centralizado
        }

function fecharImagem() {
        document.getElementById("overlay").style.display = "none"; // Oculta o overlay
        }