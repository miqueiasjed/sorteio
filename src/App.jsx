import React, { useState, useEffect } from 'react';

const App = () => {
  const [selectedNumbers, setSelectedNumbers] = useState([]);
  const [reservedNumbers, setReservedNumbers] = useState({});
  const [name, setName] = useState('');
  const [phone, setPhone] = useState('');
  const [loading, setLoading] = useState(false);

  // Estilos inline para garantir que funcionem
  const styles = {
    container: {
      maxWidth: '1200px',
      margin: '0 auto',
      padding: '20px',
      fontFamily: 'Arial, sans-serif'
    },
    header: {
      textAlign: 'center',
      marginBottom: '30px',
      backgroundColor: '#f8f9fa',
      padding: '20px',
      borderRadius: '8px'
    },
    title: {
      fontSize: '2rem',
      fontWeight: 'bold',
      color: '#333',
      marginBottom: '10px'
    },
    subtitle: {
      fontSize: '1.1rem',
      color: '#666'
    },
    updateButton: {
      backgroundColor: '#28a745',
      color: 'white',
      border: 'none',
      padding: '10px 20px',
      borderRadius: '5px',
      cursor: 'pointer',
      marginBottom: '20px'
    },
    numbersSection: {
      marginBottom: '30px'
    },
    sectionTitle: {
      fontSize: '1.5rem',
      fontWeight: 'bold',
      marginBottom: '15px',
      color: '#333'
    },
    numbersGrid: {
      display: 'grid',
      gridTemplateColumns: 'repeat(auto-fill, minmax(50px, 1fr))',
      gap: '8px',
      marginBottom: '20px'
    },
    numberButton: {
      width: '50px',
      height: '50px',
      border: '2px solid #ddd',
      borderRadius: '5px',
      cursor: 'pointer',
      fontSize: '14px',
      fontWeight: 'bold',
      display: 'flex',
      alignItems: 'center',
      justifyContent: 'center',
      transition: 'all 0.2s'
    },
    numberAvailable: {
      backgroundColor: '#f8f9fa',
      color: '#333'
    },
    numberSelected: {
      backgroundColor: '#007bff',
      color: 'white',
      borderColor: '#007bff'
    },
    numberReserved: {
      backgroundColor: '#ffc107',
      color: '#333',
      borderColor: '#ffc107',
      cursor: 'not-allowed'
    },
    numberPaid: {
      backgroundColor: '#28a745',
      color: 'white',
      borderColor: '#28a745',
      cursor: 'not-allowed'
    },
    formSection: {
      backgroundColor: '#f8f9fa',
      padding: '20px',
      borderRadius: '8px',
      marginBottom: '20px'
    },
    formGroup: {
      marginBottom: '15px'
    },
    label: {
      display: 'block',
      marginBottom: '5px',
      fontWeight: 'bold',
      color: '#333'
    },
    input: {
      width: '100%',
      padding: '10px',
      border: '1px solid #ddd',
      borderRadius: '5px',
      fontSize: '16px'
    },
    selectedInfo: {
      backgroundColor: '#e9ecef',
      padding: '10px',
      borderRadius: '5px',
      marginBottom: '15px'
    },
    reserveButton: {
      backgroundColor: '#007bff',
      color: 'white',
      border: 'none',
      padding: '12px 24px',
      borderRadius: '5px',
      cursor: 'pointer',
      fontSize: '16px',
      fontWeight: 'bold'
    },
    reserveButtonDisabled: {
      backgroundColor: '#6c757d',
      cursor: 'not-allowed'
    },
    legend: {
      display: 'flex',
      flexWrap: 'wrap',
      gap: '15px',
      marginTop: '20px'
    },
    legendItem: {
      display: 'flex',
      alignItems: 'center',
      gap: '8px'
    },
    legendColor: {
      width: '20px',
      height: '20px',
      borderRadius: '3px',
      border: '1px solid #ddd'
    }
  };

  const fetchData = async () => {
    try {
      const response = await fetch('/api/sheets/data');
      if (response.ok) {
        const data = await response.json();
        setReservedNumbers(data.reservedNumbers || {});
      }
    } catch (error) {
      console.error('Erro ao buscar dados:', error);
    }
  };

  useEffect(() => {
    fetchData();
    const interval = setInterval(fetchData, 30000);
    return () => clearInterval(interval);
  }, []);

  const toggleNumber = (number) => {
    if (reservedNumbers[number]) return;
    
    setSelectedNumbers(prev => 
      prev.includes(number) 
        ? prev.filter(n => n !== number)
        : [...prev, number]
    );
  };

  const getNumberStyle = (number) => {
    const baseStyle = { ...styles.numberButton };
    
    if (reservedNumbers[number]) {
      if (reservedNumbers[number].status === 'pago') {
        return { ...baseStyle, ...styles.numberPaid };
      } else {
        return { ...baseStyle, ...styles.numberReserved };
      }
    } else if (selectedNumbers.includes(number)) {
      return { ...baseStyle, ...styles.numberSelected };
    } else {
      return { ...baseStyle, ...styles.numberAvailable };
    }
  };

  const handleReserve = async () => {
    if (selectedNumbers.length === 0 || !name || !phone) return;

    setLoading(true);
    try {
      const response = await fetch('/api/sheets/reserve', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          numbers: selectedNumbers,
          name,
          phone,
          status: 'reservado'
        }),
      });

      if (response.ok) {
        alert('Números reservados com sucesso!');
        setSelectedNumbers([]);
        setName('');
        setPhone('');
        fetchData();
      } else {
        const error = await response.json();
        alert(error.error || 'Erro ao reservar números');
      }
    } catch (error) {
      alert('Erro ao reservar números');
    } finally {
      setLoading(false);
    }
  };

  const numbers = Array.from({ length: 200 }, (_, i) => i + 1);

  return (
    <div style={styles.container}>
      <div style={styles.header}>
        <h1 style={styles.title}>🎁 Sorteio Rede Artesanal</h1>
        <p style={styles.subtitle}>Reserve seus números da sorte! Números de 1 a 200 disponíveis.</p>
        <button style={styles.updateButton} onClick={fetchData}>
          🔄 Atualizar dados
        </button>
      </div>

      <div style={styles.numbersSection}>
        <h2 style={styles.sectionTitle}>Escolha seus números</h2>
        <p>Clique nos números para selecioná-los. Números em verde já foram pagos, em amarelo estão reservados.</p>
        
        <div style={styles.numbersGrid}>
          {numbers.map(number => (
            <button
              key={number}
              style={getNumberStyle(number)}
              onClick={() => toggleNumber(number)}
              disabled={!!reservedNumbers[number]}
            >
              {number}
            </button>
          ))}
        </div>
      </div>

      <div style={styles.formSection}>
        <h2 style={styles.sectionTitle}>Seus dados</h2>
        <p>Preencha seus dados para reservar os números selecionados</p>
        
        <div style={styles.formGroup}>
          <label style={styles.label}>Nome completo</label>
          <input
            style={styles.input}
            type="text"
            placeholder="Digite seu nome completo"
            value={name}
            onChange={(e) => setName(e.target.value)}
          />
        </div>

        <div style={styles.formGroup}>
          <label style={styles.label}>Telefone/WhatsApp</label>
          <input
            style={styles.input}
            type="tel"
            placeholder="(11) 99999-9999"
            value={phone}
            onChange={(e) => setPhone(e.target.value)}
          />
        </div>

        <div style={styles.selectedInfo}>
          <strong>Números selecionados:</strong> {selectedNumbers.join(', ') || 'Nenhum'}
        </div>

        <button
          style={{
            ...styles.reserveButton,
            ...(selectedNumbers.length === 0 || !name || !phone || loading ? styles.reserveButtonDisabled : {})
          }}
          onClick={handleReserve}
          disabled={selectedNumbers.length === 0 || !name || !phone || loading}
        >
          {loading ? 'Reservando...' : `Reservar ${selectedNumbers.length} número(s)`}
        </button>
      </div>

      <div>
        <h3 style={styles.sectionTitle}>Legenda</h3>
        <div style={styles.legend}>
          <div style={styles.legendItem}>
            <div style={{...styles.legendColor, backgroundColor: '#f8f9fa'}}></div>
            <span>Disponível</span>
          </div>
          <div style={styles.legendItem}>
            <div style={{...styles.legendColor, backgroundColor: '#007bff'}}></div>
            <span>Selecionado</span>
          </div>
          <div style={styles.legendItem}>
            <div style={{...styles.legendColor, backgroundColor: '#ffc107'}}></div>
            <span>Reservado</span>
          </div>
          <div style={styles.legendItem}>
            <div style={{...styles.legendColor, backgroundColor: '#28a745'}}></div>
            <span>Pago</span>
          </div>
        </div>
      </div>
    </div>
  );
};

export default App;

