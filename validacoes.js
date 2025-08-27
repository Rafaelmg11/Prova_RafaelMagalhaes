function validarFuncionario() {
    let nome = document.getElementById("nome_funcionario").value;
    let telefone = document.getElementById("telefone").value;
    let email = document.getElementById("email").value;

    if (nome.length < 3) {
        alert("O nome do funcionário deve ter pelo menos 3 caracteres.");
        return false;
    }

    let regexTelefone = /^[0-9]{10,11}$/;
    if (!regexTelefone.test(telefone)) {
        alert("Digite um telefone válido (10 ou 11 dígitos).");
        return false;
    }

    let regexEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!regexEmail.test(email)) {
        alert("Digite um e-mail válido.");
        return false;
    }

    return true;
}


// Função que bloqueia letras e números juntos
function letras_numeros() {
    const form = document.getElementById("form_busca");
    if (!form) return; // garante que o formulário existe

    form.addEventListener("submit", function(e) {
        const input = document.getElementById("busca").value.trim();

        const temLetra = /[A-Za-z]/.test(input);
        const temNumero = /[0-9]/.test(input);

        if (temLetra && temNumero) {
            alert("Erro: não é permitido usar letras e números juntos!");
            e.preventDefault(); // bloqueia envio do formulário
        }
    });
}

// Chama a função quando a página termina de carregar
window.addEventListener("DOMContentLoaded", letras_numeros);


// Função que bloqueia letras e números juntos
function sem_numeros() {
    const form = document.getElementById("form_nome");
    if (!form) return; // garante que o formulário existe

    form.addEventListener("submit", function(e) {
        const input = document.getElementById("nome").value.trim();


        const temNumero = /[0-9]/.test(input);

        if (temNumero) {
            alert("Erro: não é permitido usar números no campo de nome!");
            e.preventDefault(); // bloqueia envio do formulário
        }
    });
}

window.addEventListener("DOMContentLoaded", function() {
    sem_numeros();
});

// EXECUTAR MASCARAS TELEFONE
function mascara(o,f){
    objeto = o
    funcao = f
    setTimeout("executaMascara()",1)
}

function executaMascara(){
    objeto.value=funcao(objeto.value)
}

function celular(variavel){
    variavel = variavel.replace(/\D/g,"")
    variavel = variavel.replace(/^(\d\d)(\d)/g,"($1)$2") // adiciona parenteses em volta dos dois primeiros dígitos.
    variavel = variavel.replace(/(\d{4})(\d)/,"$1-$2") // adiciona hifen entre o quarto e quinto dígito
    return variavel
}