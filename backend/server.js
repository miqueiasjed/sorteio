const express = require('express')
const cors = require('cors')
const { google } = require('googleapis')
const path = require('path')

const app = express()
const PORT = process.env.PORT || 3001

// Middleware
app.use(cors())
app.use(express.json())

// Configuração do Google Sheets
const SPREADSHEET_ID = '1twQGhsOuGw7yMGTgOgJShmC3GwPHrsX-0b_ImTCc3Yk'
const RANGE = 'Página1!A:D'

// Configurar autenticação
const auth = new google.auth.GoogleAuth({
  keyFile: path.join(__dirname, '../client_secret.json'),
  scopes: ['https://www.googleapis.com/auth/spreadsheets'],
})

const sheets = google.sheets({ version: 'v4', auth })

// Rota para buscar dados da planilha
app.get('/api/sheets/data', async (req, res) => {
  try {
    const response = await sheets.spreadsheets.values.get({
      spreadsheetId: SPREADSHEET_ID,
      range: RANGE,
    })

    const rows = response.data.values || []
    const reservedNumbers = {}

    // Processar dados (pular cabeçalho se existir)
    rows.slice(1).forEach(row => {
      if (row[0]) { // Se há um número
        const number = parseInt(row[0])
        const name = row[1] || ''
        const phone = row[2] || ''
        const status = row[3] || 'disponivel'
        
        if (status !== 'disponivel') {
          reservedNumbers[number] = { name, phone, status }
        }
      }
    })

    res.json({ reservedNumbers })
  } catch (error) {
    console.error('Erro ao buscar dados:', error)
    res.status(500).json({ error: 'Erro ao buscar dados da planilha' })
  }
})

// Rota para reservar números
app.post('/api/sheets/reserve', async (req, res) => {
  try {
    const { numbers, name, phone, status } = req.body

    // Primeiro, verificar se os números já estão reservados
    const currentData = await sheets.spreadsheets.values.get({
      spreadsheetId: SPREADSHEET_ID,
      range: RANGE,
    })

    const rows = currentData.data.values || []
    const existingNumbers = new Set()
    
    rows.slice(1).forEach(row => {
      if (row[0] && row[3] && row[3] !== 'disponivel') {
        existingNumbers.add(parseInt(row[0]))
      }
    })

    // Verificar se algum número já está reservado
    const conflictNumbers = numbers.filter(num => existingNumbers.has(num))
    if (conflictNumbers.length > 0) {
      return res.status(400).json({ 
        error: `Números ${conflictNumbers.join(', ')} já estão reservados` 
      })
    }

    // Adicionar novos números
    const newRows = numbers.map(number => [number, name, phone, status])
    
    await sheets.spreadsheets.values.append({
      spreadsheetId: SPREADSHEET_ID,
      range: RANGE,
      valueInputOption: 'RAW',
      resource: {
        values: newRows
      }
    })

    res.json({ success: true, message: 'Números reservados com sucesso' })
  } catch (error) {
    console.error('Erro ao reservar números:', error)
    res.status(500).json({ error: 'Erro ao reservar números' })
  }
})

// Rota para atualizar status
app.post('/api/sheets/update-status', async (req, res) => {
  try {
    const { number, status } = req.body

    // Buscar a linha do número
    const response = await sheets.spreadsheets.values.get({
      spreadsheetId: SPREADSHEET_ID,
      range: RANGE,
    })

    const rows = response.data.values || []
    let rowIndex = -1

    for (let i = 1; i < rows.length; i++) {
      if (parseInt(rows[i][0]) === number) {
        rowIndex = i + 1 // +1 porque as linhas começam em 1
        break
      }
    }

    if (rowIndex === -1) {
      return res.status(404).json({ error: 'Número não encontrado' })
    }

    // Atualizar o status
    await sheets.spreadsheets.values.update({
      spreadsheetId: SPREADSHEET_ID,
      range: `Página1!D${rowIndex}`,
      valueInputOption: 'RAW',
      resource: {
        values: [[status]]
      }
    })

    res.json({ success: true, message: 'Status atualizado com sucesso' })
  } catch (error) {
    console.error('Erro ao atualizar status:', error)
    res.status(500).json({ error: 'Erro ao atualizar status' })
  }
})

app.listen(PORT, () => {
  console.log(`Servidor rodando na porta ${PORT}`)
})

