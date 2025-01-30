// Autouzupełnianie dla pola tekstowego
$(document).ready(function () {
    $("#playerNameInput").autocomplete({
        source: async function (request, response) {
            try {
                const res = await fetch(`/getPlayers?query=${request.term}`);
                const data = await res.json();
                response(data.players || []); // API zwraca { players: ["Gracz 1", "Gracz 2"] }
            } catch (error) {
                console.error("Błąd w autouzupełnianiu:", error);
            }
        },
        minLength: 1, // Minimalna liczba znaków przed wyświetleniem propozycji
        delay: 300, // Opóźnienie w milisekundach
    });
});

document.getElementById('guesstransferForm').addEventListener('submit', async (e) => {
    e.preventDefault();

    const playerNameInput = document.getElementById('playerNameInput');
    if (!playerNameInput) {
        console.error('Element #playerNameInput nie został znaleziony!');
        return; // Zatrzymujemy skrypt, jeśli element nie istnieje
    }
    const playerName = playerNameInput.value.trim();

    console.log('Wprowadzona nazwa piłkarza:', playerName); // Logowanie wartości

    if (!playerName) {
        showMessage('Podaj nazwisko piłkarza!', 'error');
        return;
    }

    try {
        const response = await fetch('/guess', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ player_name: playerName }), // Logowane dane
        });

        const result = await response.json();
        console.log('Odpowiedź z serwera:', result); // Logowanie odpowiedzi

        // Sprawdzamy, czy odpowiedź zawiera błąd
        if (result.error) {
            showMessage(result.error, 'error');
        } else {
            if (result.game_over) {
                if (result.guessed_correctly && result.remaining_attempts >= 0) {
                    showMessage('Gra zakończona! Udało Ci się zgadnąć! Dziękujemy za grę! Piłkarzem do zgadnięcia był: ' + result.correct_player, 'success');
                } else if (result.remaining_attempts <= 0) {
                    showMessage('Gra zakończona! Przekroczyłeś limit prób. Spróbuj ponownie jutro. Piłkarzem do zgadnięcia był: ' + result.correct_player, 'error');
                }
                document.getElementById('remainingAttempts').textContent = result.remaining_attempts;
                document.getElementById('playerNameInput').disabled = true; // Blokujemy input
                document.querySelector('button[type="submit"]').disabled = true; // Blokujemy przycisk
            } else {
                if (result.guessed_correctly) {
                    showMessage('Zgadłeś poprawnie!', 'success');
                } else {
                    showMessage(`Błędna odpowiedź! Pozostałe próby: ${result.remaining_attempts}`, 'error');
                }

                document.getElementById('remainingAttempts').textContent = result.remaining_attempts;
            }
        }

    } catch (error) {
        console.error('Błąd podczas wysyłania żądania:', error);
        showMessage('Wystąpił błąd: ' + error.message, 'error');
    }
});

function showMessage(message, type = 'error') {
    const messageContainer = document.getElementById('message-container');
    messageContainer.textContent = message;

    messageContainer.className = `message ${type}`;
    messageContainer.style.display = 'block';
    messageContainer.classList.remove('hidden');

    messageContainer.style.top = '25%'; 
    messageContainer.style.opacity = '1';
    messageContainer.style.transform = 'translateX(-50%)';

    // Ukryj wiadomość po 3 sekundach
    setTimeout(() => {
        messageContainer.style.opacity = '0';
        messageContainer.style.transform = 'translateX(-50%) translateY(-10px)';
        setTimeout(() => {
            messageContainer.style.display = 'none';
        }, 500); // Czas na zakończenie animacji
    }, 5000);

}


document.addEventListener("mousemove", (e) => {
    let cursor = document.querySelector(".custom-cursor");
    if (!cursor) {
        cursor = document.createElement("div");
        cursor.classList.add("custom-cursor");
        document.body.appendChild(cursor);
    }
    cursor.style.transform = `translate(${e.clientX}px, ${e.clientY}px)`;
});

document.addEventListener("mousemove", (e) => {
    let trail = document.createElement("div");
    trail.classList.add("cursor-trail");
    document.body.appendChild(trail);
    
    trail.style.left = `${e.clientX}px`;
    trail.style.top = `${e.clientY}px`;

    setTimeout(() => {
        trail.remove();
    }, 500);
});


document.addEventListener("DOMContentLoaded", () => {
    console.log("Script2 działa!");

    let cursor = document.querySelector(".custom-cursor");
    if (!cursor) {
        cursor = document.createElement("div");
        cursor.classList.add("custom-cursor");
        document.body.appendChild(cursor);
    }

    document.addEventListener("mousemove", (e) => {
        cursor.style.transform = `translate(${e.clientX}px, ${e.clientY}px)`;
    });

    console.log("Kursor został dodany do dokumentu.");
});