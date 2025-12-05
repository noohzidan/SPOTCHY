// FIXED: More complex, unpredictable algorithm
function generateSecureToken(phrase) {
    // Add multiple transformations and randomness
    const salt = window.performance.now().toString(36) + Math.random().toString(36);
    const data = phrase + salt + Date.now();
    
    // Multiple hash-like transformations
    let hash = 0;
    for (let i = 0; i < data.length; i++) {
        const char = data.charCodeAt(i);
        hash = ((hash << 5) - hash) + char;
        hash = hash & hash; // Convert to 32-bit integer
    }
    
    // Additional transformations
    const transformed = btoa(phrase + '|' + hash + '|' + salt)
        .replace(/=/g, '')
        .split('').reverse().join('');
    
    return transformed.substr(0, 32); // Fixed length
}

// FIXED: Remove predictable timing
function updateToken() {
    const phrase = document.getElementById('phrase').value;
    const token = generateSecureToken(phrase);
    document.getElementById('token').value = token;
}

// Update token on input with debounce
let timeoutId;
document.getElementById('phrase').addEventListener('input', function() {
    clearTimeout(timeoutId);
    timeoutId = setTimeout(updateToken, 100); // Debounce
});

// Initial token generation
updateToken();
