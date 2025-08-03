# Sorteio Rede Artesanal

Sistema completo de sorteio online com números de 1 a 200, integrado ao Google Sheets para gerenciamento de reservas e pagamentos.

## 🎯 Funcionalidades

- **Interface intuitiva**: Seleção visual de números com cores indicativas
- **Integração Google Sheets**: Dados salvos automaticamente na planilha
- **Gerenciamento de status**: Controle de números reservados e pagos
- **Atualização em tempo real**: Sincronização automática a cada 30 segundos
- **Design responsivo**: Funciona em desktop e mobile

## 🎨 Status dos Números

- **Cinza**: Disponível para reserva
- **Azul**: Selecionado pelo usuário atual
- **Amarelo**: Reservado (aguardando pagamento)
- **Verde**: Pago (confirmado)

## 🔧 Tecnologias Utilizadas

### Frontend
- React 18
- Tailwind CSS
- shadcn/ui components
- Lucide React icons
- Vite

### Backend
- Flask (Python)
- Google Sheets API
- Google OAuth2

## 📋 Como Usar

### Para Participantes

1. Acesse o link da aplicação
2. Clique nos números desejados (aparecem em azul)
3. Preencha seu nome completo e telefone
4. Clique em "Reservar números"
5. Aguarde a confirmação

### Para Administradores

1. Acesse a planilha do Google Sheets
2. Visualize todas as reservas com dados dos participantes
3. Altere o status de "reservado" para "pago" quando receber o pagamento
4. Os números pagos aparecerão em verde na aplicação

## 🔗 Links Importantes

- **Aplicação**: https://5000-irn601oo6omca7xtbnqtl-d6096d8b.manusvm.computer
- **Planilha Google Sheets**: https://docs.google.com/spreadsheets/d/1twQGhsOuGw7yMGTgOgJShmC3GwPHrsX-0b_ImTCc3Yk/edit

## 📊 Estrutura da Planilha

A planilha possui as seguintes colunas:
- **Coluna A**: Número sorteado (1-200)
- **Coluna B**: Nome completo do participante
- **Coluna C**: Telefone/WhatsApp
- **Coluna D**: Status (reservado/pago)

## 🚀 Instalação Local

```bash
# Clone o repositório
git clone [url-do-repositorio]

# Instale as dependências do frontend
cd sorteio-rede-artesanal
npm install

# Configure o ambiente Python
python3 -m venv venv
source venv/bin/activate
pip install -r requirements.txt

# Configure as credenciais do Google Sheets
# Coloque o arquivo client_secret.json na raiz do projeto

# Execute o frontend (desenvolvimento)
npm run dev

# Execute o backend
python app.py
```

## 🔐 Configuração do Google Sheets

1. Crie um projeto no Google Cloud Console
2. Ative as APIs do Google Sheets e Google Drive
3. Crie uma conta de serviço e baixe o arquivo JSON
4. Compartilhe a planilha com o email da conta de serviço
5. Renomeie o arquivo para `client_secret.json`

## 💡 Dicas Adicionais

- **Backup**: A planilha do Google Sheets serve como backup automático
- **Controle**: Você pode editar diretamente na planilha para fazer ajustes
- **Relatórios**: Use as funcionalidades do Google Sheets para gerar relatórios
- **Notificações**: Configure notificações na planilha para ser alertado sobre novas reservas

## 📱 Responsividade

A aplicação foi desenvolvida para funcionar perfeitamente em:
- Computadores desktop
- Tablets
- Smartphones

## 🎉 Pronto para Usar!

O sistema está completamente funcional e pronto para seu sorteio. Basta compartilhar o link da aplicação com os participantes e acompanhar as reservas pela planilha do Google Sheets.

