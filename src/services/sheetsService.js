// Configuração para integração com Google Sheets
const SPREADSHEET_ID = '1twQGhsOuGw7yMGTgOgJShmC3GwPHrsX-0b_ImTCc3Yk'
const API_BASE_URL = '/api/sheets' // Será implementado no backend

// Função para buscar dados da planilha
export const fetchSheetData = async () => {
  try {
    const response = await fetch(`${API_BASE_URL}/data`)
    if (!response.ok) {
      throw new Error('Erro ao buscar dados da planilha')
    }
    return await response.json()
  } catch (error) {
    console.error('Erro ao buscar dados:', error)
    throw error
  }
}

// Função para reservar números
export const reserveNumbers = async (numbers, name, phone) => {
  try {
    const response = await fetch(`${API_BASE_URL}/reserve`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        numbers,
        name,
        phone,
        status: 'reservado'
      })
    })
    
    if (!response.ok) {
      throw new Error('Erro ao reservar números')
    }
    
    return await response.json()
  } catch (error) {
    console.error('Erro ao reservar números:', error)
    throw error
  }
}

// Função para atualizar status de um número
export const updateNumberStatus = async (number, status) => {
  try {
    const response = await fetch(`${API_BASE_URL}/update-status`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        number,
        status
      })
    })
    
    if (!response.ok) {
      throw new Error('Erro ao atualizar status')
    }
    
    return await response.json()
  } catch (error) {
    console.error('Erro ao atualizar status:', error)
    throw error
  }
}

