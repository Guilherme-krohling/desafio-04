// Função para mostrar/ocultar senha
function togglePassword(inputId, buttonId) {
    const passwordInput = document.getElementById(inputId);
    const toggleButton = document.getElementById(buttonId);
   
    if (passwordInput && toggleButton) {
        toggleButton.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
           
            // Alterar ícone
            const icon = this.querySelector('i');
            if (type === 'text') {
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        });
    }
}
 
// Toggle para senha na tela de login
document.addEventListener('DOMContentLoaded', function() {
    togglePassword('senha', 'toggleSenha');
    togglePassword('senhaCadastro', 'toggleSenhaCadastro');
   
    // Código de login removido para evitar conflitos
   
    // Formulário de cadastro
    const cadastroForm = document.getElementById('cadastroForm');
    if (cadastroForm) {
        cadastroForm.addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Cadastro realizado com sucesso!');
            window.location.href = 'login.php';
        });
    }
   
    // Verificação de autenticação removida - usando sessões PHP no servidor
   
    // Botão sair
    const btnSair = document.getElementById('sair');
    if (btnSair) {
        btnSair.addEventListener('click', function() {
            // Fazer logout via PHP ao invés de localStorage
            window.location.href = 'logout.php';
        });
    }
   
    // Gerenciamento de tarefas
    if (window.location.pathname.includes('index.php')) {
        gerenciarTarefas();
    }
});
 
// Função para gerenciar tarefas
function gerenciarTarefas() {
    let tarefas = JSON.parse(localStorage.getItem('tarefas')) || [];
   
    // Formulário para adicionar tarefa
    const tarefaForm = document.getElementById('tarefaForm');
    const listaTarefas = document.getElementById('listaTarefas');
   
    // Renderizar tarefas
    function renderizarTarefas() {
        listaTarefas.innerHTML = '';
       
        if (tarefas.length === 0) {
            listaTarefas.innerHTML = `
                <div class="alert alert-info text-center">
                    Nenhuma tarefa cadastrada. Adicione uma nova tarefa acima.
                </div>
            `;
            return;
        }
       
        tarefas.forEach((tarefa, index) => {
            const data = new Date(tarefa.data).toLocaleDateString('pt-BR');
           
            const tarefaElement = document.createElement('div');
            tarefaElement.className = `card mb-3 tarefa-item ${tarefa.concluida ? 'tarefa-concluida' : ''}`;
            tarefaElement.innerHTML = `
            <div class="card-body">
                <h5 class="card-title tarefa-titulo mb-2">${tarefa.titulo}</h5>
                <p class="card-text tarefa-descricao">${tarefa.descricao}</p>
                <small class="text-muted d-block mb-2">Criada em: ${data}</small>
               
                <div class="tarefa-botoes">
                    <button class="btn btn-sm btn-outline-primary editar" data-index="${index}">
                        <i class="bi bi-pencil me-1"></i> Editar
                    </button>
                    <button class="btn btn-sm ${tarefa.concluida ? 'btn-warning' : 'btn-success'} toggle-status" data-index="${index}">
                        <i class="bi ${tarefa.concluida ? 'bi-arrow-counterclockwise' : 'bi-check-circle'} me-1"></i>
                        ${tarefa.concluida ? 'Voltar para Pendente' : 'Marcar como Concluída'}
                    </button>
                    <button class="btn btn-sm btn-outline-danger excluir" data-index="${index}">
                        <i class="bi bi-trash me-1"></i> Excluir
                    </button>
                </div>
            </div>
        `;
           
            listaTarefas.appendChild(tarefaElement);
        });
       
        // Adicionar event listeners aos botões
        document.querySelectorAll('.editar').forEach(btn => {
            btn.addEventListener('click', function() {
                const index = this.getAttribute('data-index');
                abrirModalEdicao(index);
            });
        });
       
        document.querySelectorAll('.toggle-status').forEach(btn => {
            btn.addEventListener('click', function() {
                const index = this.getAttribute('data-index');
                toggleStatusTarefa(index);
            });
        });
       
        document.querySelectorAll('.excluir').forEach(btn => {
            btn.addEventListener('click', function() {
                const index = this.getAttribute('data-index');
                excluirTarefa(index);
            });
        });
    }
   
    // Adicionar nova tarefa
    tarefaForm.addEventListener('submit', function(e) {
        e.preventDefault();
       
        const titulo = document.getElementById('titulo').value;
        const descricao = document.getElementById('descricao').value;
       
        if (titulo && descricao) {
            const novaTarefa = {
                titulo,
                descricao,
                data: new Date().toISOString(),
                concluida: false
            };
           
            tarefas.push(novaTarefa);
            localStorage.setItem('tarefas', JSON.stringify(tarefas));
           
            renderizarTarefas();
            tarefaForm.reset();
        }
    });
   
    // Abrir modal de edição
    function abrirModalEdicao(index) {
        const tarefa = tarefas[index];
       
        document.getElementById('editId').value = index;
        document.getElementById('editTitulo').value = tarefa.titulo;
        document.getElementById('editDescricao').value = tarefa.descricao;
       
        const modal = new bootstrap.Modal(document.getElementById('editarTarefaModal'));
        modal.show();
    }
   
    // Salvar edição da tarefa
    document.getElementById('salvarEdicao').addEventListener('click', function() {
        const index = document.getElementById('editId').value;
        const titulo = document.getElementById('editTitulo').value;
        const descricao = document.getElementById('editDescricao').value;
       
        if (titulo && descricao) {
            tarefas[index].titulo = titulo;
            tarefas[index].descricao = descricao;
           
            localStorage.setItem('tarefas', JSON.stringify(tarefas));
            renderizarTarefas();
           
            const modal = bootstrap.Modal.getInstance(document.getElementById('editarTarefaModal'));
            modal.hide();
        }
    });
   
    // Alternar status da tarefa (concluída/pendente)
    function toggleStatusTarefa(index) {
        tarefas[index].concluida = !tarefas[index].concluida;
        localStorage.setItem('tarefas', JSON.stringify(tarefas));
        renderizarTarefas();
    }
   
    // Excluir tarefa
    function excluirTarefa(index) {
        if (confirm('Tem certeza que deseja excluir esta tarefa?')) {
            tarefas.splice(index, 1);
            localStorage.setItem('tarefas', JSON.stringify(tarefas));
            renderizarTarefas();
        }
    }
   
    // Renderizar tarefas inicialmente
    renderizarTarefas();
}