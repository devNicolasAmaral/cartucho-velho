function mostrarToast(texto, tipo = 'success', titulo = 'Notificação') {
    const toastLiveExample = document.getElementById('liveToast');
    const toastHeader = toastLiveExample.querySelector('.toast-header');
    
    // Define o título padrão baseado no tipo, se não for fornecido
    if (titulo === 'Notificação') 
        titulo = ucfirst(tipo === 'danger' ? 'Erro' : (tipo === 'warning' ? 'Atenção' : 'Sucesso'));
    
    const headerClass = `text-bg-${tipo}`;

    document.getElementById('toastTitulo').innerText = titulo;
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