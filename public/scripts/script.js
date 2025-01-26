// public/scripts/script.js

// Funkcja do otwierania i zamykania modalnego okienka
const kafeleIcon = document.getElementById('kafeleIcon');
const myModal = document.getElementById('myModal');
const closeModal = document.getElementById('closeModal');

// Otwórz modal po kliknięciu na ikonę
if (kafeleIcon && myModal && closeModal) {
    kafeleIcon.addEventListener('click', () => {
        myModal.style.display = 'flex';
    });

    // Zamknij modal po kliknięciu na "x"
    closeModal.addEventListener('click', () => {
        myModal.style.display = 'none';
    });

    // Zamknij modal, jeśli kliknięto poza okienkiem
    window.addEventListener('click', (event) => {
        if (event.target === myModal) {
            myModal.style.display = 'none';
        }
    });
}

// Autouzupełnianie dla pola tekstowego
$(document).ready(function () {
    $("#playerNameInput").autocomplete({
        source: async function (request, response) {
            try {
                const res = await fetch(`/getPlayers?query=${request.term}`);
                const data = await res.json();
                response(data.players); // API zwraca { players: ["Gracz 1", "Gracz 2"] }
            } catch (error) {
                console.error("Błąd w autouzupełnianiu:", error);
            }
        },
        minLength: 2, // Minimalna liczba znaków przed wyświetleniem propozycji
        delay: 300, // Opóźnienie w milisekundach
    });
});

// Obsługa formularza zgadywania
document.getElementById('guessForm').addEventListener('submit', async (e) => {
    e.preventDefault();  // Zapobiega domyślnemu wysyłaniu formularza

    // Sprawdzanie, czy gra jest zakończona (czy w sesji jest ustawione 'game_over')
    const gameOver = JSON.parse(document.getElementById('gameOverState').textContent || 'false');
    if (gameOver) {
        alert("Zgadłeś piłkarza! Gra zakończona.");
        return;  // Nie pozwalamy na dalsze zgadywanie
    }

    const playerName = e.target.player_name.value.trim();

    if (!playerName) {
        alert('Podaj nazwisko piłkarza!');
        return;
    }

    try {
        const response = await fetch('/checkGuess', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ player_name: playerName })
        });

        if (!response.ok) {
            throw new Error(`Błąd serwera: ${response.statusText}`);
        }

        const result = await response.json();
        const resultsDiv = document.getElementById('results');

        if (result.error) {
            // Jeśli błąd to użytkownik zgadywał już dzisiaj
            if (result.error.includes('Dzisiaj już próbowałeś')) {
                resultsDiv.innerHTML = `<div class="error">${result.error}</div>`;
                return;  // Jeśli błąd to już zgadywał dzisiaj, zatrzymujemy dalsze wykonywanie
            }

            // Obsługa innych błędów
            resultsDiv.innerHTML = `<div class="error">${result.error}</div>`;
            return;
        }

        let resultHtml = `
            <div class="result-item ${result.matches.country ? 'green' : 'red'}">
                Kraj: ${result.country || 'Brak danych'}
            </div>
            <div class="result-item ${result.matches.league ? 'green' : 'red'}">
                Liga: ${result.league || 'Brak danych'}
            </div>
            <div class="result-item ${result.matches.club ? 'green' : 'red'}">
                Klub: ${result.club || 'Brak danych'}
            </div>
            <div class="result-item ${result.matches.position ? 'green' : 'red'}">
                Pozycja: ${result.position || 'Brak danych'}
            </div>
            <div class="result-item ${result.matches.age ? 'green' : 'red'}">
                Wiek: ${result.age || 'Brak danych'}
            </div>
            <div class="result-item ${result.matches.shirt_number ? 'green' : 'red'}">
                Numer: ${result.shirt_number || 'Brak danych'}
            </div>
        `;

        if (result.game_over) {
            resultHtml += "<div class='game-over'>Brawo! Zgadłeś piłkarza!</div>";
            // Blokujemy formularz po zgadnięciu
            document.querySelector('input[type="text"]').disabled = true;
            document.querySelector('button').disabled = true;
        }

        resultsDiv.innerHTML += `<div class="attempt">${resultHtml}</div>`;
        resultsDiv.scrollTop = resultsDiv.scrollHeight;

    } catch (error) {
        console.error('Błąd przy przetwarzaniu odpowiedzi:', error);
        const resultsDiv = document.getElementById('results');
        resultsDiv.innerHTML = `<div class="error">Wystąpił błąd: ${error.message}</div>`;
    }

    e.target.reset();
});
