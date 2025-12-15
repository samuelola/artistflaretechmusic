let lastStatus = navigator.onLine ? "online" : "offline"; // initial state, no popup

function playSound(type) {
    const sounds = {
        success: document.getElementById("sound-success"),
        error: document.getElementById("sound-error"),
    };
    if (sounds[type]) {
        sounds[type].currentTime = 0;
        sounds[type].play();
    }
}

function showToast(message, type = "success") {
    const container = document.getElementById("toastContainer");
    const toast = document.createElement("div");
    toast.className = "toast " + type;
    toast.textContent = message;
    container.appendChild(toast);
    //playSound(type);
    setTimeout(() => toast.remove(), 4000);
}

function showStatus(message, cssClass, autoHide = false) {
    const statusDiv = document.getElementById("connectionStatus");
    statusDiv.textContent = message;
    statusDiv.className = "status " + cssClass;
    statusDiv.style.display = "flex";

    if (autoHide) {
        setTimeout(() => {
            statusDiv.style.display = "none";
        }, 3000);
    }
}

function handleOffline() {
    if (lastStatus !== "offline") {
        showStatus("❌ You are Offline (no internet)", "offline");
        showToast("You are offline ❌", "error");
        lastStatus = "offline";
    }
}

function handleOnline() {
    if (lastStatus !== "online") {
        showStatus("✅ You are back Online", "online", true);
        showToast("Back online ✅", "success");
        lastStatus = "online";
    }
}

// Listen only to changes
window.addEventListener("offline", handleOffline);
window.addEventListener("online", handleOnline);
