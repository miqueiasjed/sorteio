from flask import Flask, send_from_directory, request, jsonify
from flask_cors import CORS
import json
import os
from google.oauth2.service_account import Credentials
from googleapiclient.discovery import build

app = Flask(__name__, static_folder='dist')
CORS(app)

# Configuração do Google Sheets
SPREADSHEET_ID = '1twQGhsOuGw7yMGTgOgJShmC3GwPHrsX-0b_ImTCc3Yk'
RANGE = 'Folha1!A:D'

# Configurar autenticação
credentials = Credentials.from_service_account_file(
    'backend/client_secret.json',
    scopes=['https://www.googleapis.com/auth/spreadsheets']
)
service = build('sheets', 'v4', credentials=credentials)

@app.route('/')
def serve_frontend():
    return send_from_directory(app.static_folder, 'index.html')

@app.route('/<path:path>')
def serve_static(path):
    if os.path.exists(os.path.join(app.static_folder, path)):
        return send_from_directory(app.static_folder, path)
    else:
        return send_from_directory(app.static_folder, 'index.html')

@app.route('/api/sheets/data', methods=['GET'])
def get_sheet_data():
    try:
        result = service.spreadsheets().values().get(
            spreadsheetId=SPREADSHEET_ID,
            range=RANGE
        ).execute()
        
        rows = result.get('values', [])
        reserved_numbers = {}
        
        # Processar dados (pular cabeçalho se existir)
        for row in rows:
            if len(row) > 0 and row[0]:  # Se há um número
                try:
                    number = int(row[0])
                    name = row[1] if len(row) > 1 else ''
                    phone = row[2] if len(row) > 2 else ''
                    status = row[3] if len(row) > 3 else 'disponivel'
                    
                    # Incluir todos os números que não estão disponíveis
                    if status and status.lower() != 'disponivel':
                        reserved_numbers[number] = {'name': name, 'phone': phone, 'status': status}
                except ValueError:
                    continue
        
        return jsonify({'reservedNumbers': reserved_numbers})
    except Exception as e:
        print(f'Erro ao buscar dados: {e}')
        return jsonify({'error': 'Erro ao buscar dados da planilha'}), 500

@app.route('/api/sheets/reserve', methods=['POST'])
def reserve_numbers():
    try:
        data = request.get_json()
        numbers = data.get('numbers', [])
        name = data.get('name', '')
        phone = data.get('phone', '')
        status = data.get('status', 'reservado')
        
        # Primeiro, verificar se os números já estão reservados
        current_result = service.spreadsheets().values().get(
            spreadsheetId=SPREADSHEET_ID,
            range=RANGE
        ).execute()
        
        rows = current_result.get('values', [])
        existing_numbers = set()
        
        for row in rows:
            if len(row) > 0 and row[0]:
                try:
                    number = int(row[0])
                    status = row[3] if len(row) > 3 else 'disponivel'
                    # Considerar qualquer status que não seja vazio ou 'disponivel' como reservado
                    if status and status.lower() != 'disponivel':
                        existing_numbers.add(number)
                except ValueError:
                    continue
        
        # Verificar se algum número já está reservado
        conflict_numbers = [num for num in numbers if num in existing_numbers]
        if conflict_numbers:
            return jsonify({
                'error': f'Números {", ".join(map(str, conflict_numbers))} já estão reservados'
            }), 400
        
        # Adicionar novos números
        new_rows = [[number, name, phone, status] for number in numbers]
        
        service.spreadsheets().values().append(
            spreadsheetId=SPREADSHEET_ID,
            range=RANGE,
            valueInputOption='RAW',
            body={'values': new_rows}
        ).execute()
        
        return jsonify({'success': True, 'message': 'Números reservados com sucesso'})
    except Exception as e:
        print(f'Erro ao reservar números: {e}')
        return jsonify({'error': 'Erro ao reservar números'}), 500

@app.route('/api/sheets/update-status', methods=['POST'])
def update_status():
    try:
        data = request.get_json()
        number = data.get('number')
        status = data.get('status')
        
        # Buscar a linha do número
        result = service.spreadsheets().values().get(
            spreadsheetId=SPREADSHEET_ID,
            range=RANGE
        ).execute()
        
        rows = result.get('values', [])
        row_index = -1
        
        for i, row in enumerate(rows[1:], start=1):
            if len(row) > 0 and row[0]:
                try:
                    if int(row[0]) == number:
                        row_index = i + 1  # +1 porque as linhas começam em 1
                        break
                except ValueError:
                    continue
        
        if row_index == -1:
            return jsonify({'error': 'Número não encontrado'}), 404
        
        # Atualizar o status
        service.spreadsheets().values().update(
            spreadsheetId=SPREADSHEET_ID,
            range=f'Folha1!D{row_index}',
            valueInputOption='RAW',
            body={'values': [[status]]}
        ).execute()
        
        return jsonify({'success': True, 'message': 'Status atualizado com sucesso'})
    except Exception as e:
        print(f'Erro ao atualizar status: {e}')
        return jsonify({'error': 'Erro ao atualizar status'}), 500

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000, debug=False)

