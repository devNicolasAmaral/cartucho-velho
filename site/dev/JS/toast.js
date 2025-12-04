function mostrarToast(texto, tipo = 'success', titulo = 'Notificação', icone = null) {
    const toastLiveExample = document.getElementById('liveToast');
    const toastHeader = toastLiveExample.querySelector('.toast-header');
    
    // Define o título padrão baseado no tipo, se não for fornecido
    if (titulo === 'Notificação') {
        if (tipo === 'danger') titulo = 'Erro';
        else if (tipo === 'warning') titulo = 'Atenção';
        else if (tipo === 'info') titulo = 'Informação';
        else titulo = 'Sucesso';
    }
    
    // Define ícone baseado no texto se não for fornecido
    if (!icone) {
        const textoLower = texto.toLowerCase();
        if (textoLower.includes('contraste')) {
            icone = 'bi-highlights';
        } else if (textoLower.includes('musica') || textoLower.includes('música')) {
            if (textoLower.includes('desativada')) icone = 'bi-volume-mute-fill';
            else icone = 'bi-volume-up-fill';
        } else if (tipo === 'success') {
            icone = 'bi-check-circle-fill';
        } else if (tipo === 'danger') {
            icone = 'bi-x-circle-fill';
        } else if (tipo === 'warning') {
            icone = 'bi-exclamation-triangle-fill';
        } else {
            icone = 'bi-info-circle-fill';
        }
    }
    
    const headerClass = `text-bg-${tipo}`;

    const tituloElement = document.getElementById('toastTitulo');
    tituloElement.innerHTML = `<i class="bi ${icone} me-2"></i>${titulo}`;
    document.getElementById('toastCorpo').innerText = texto;
    
    // Remove classes de cor antigas e adiciona a nova
    toastHeader.classList.remove('text-bg-success', 'text-bg-danger', 'text-bg-warning', 'text-bg-info');
    toastHeader.classList.add(headerClass);

    const toast = new bootstrap.Toast(toastLiveExample);
    toast.show();
}

// Função auxiliar para deixar a primeira letra maiúscula (o PHP faz isso, o JS não)
function ucfirst(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}