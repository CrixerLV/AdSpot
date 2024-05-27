function showLoadingOverlay() {
    const overlay = document.createElement("div");
    overlay.className = "loading-overlay";
    overlay.innerHTML = '<div class="loader"></div>';
    document.body.appendChild(overlay);
  
    setTimeout(() => {
      document.body.removeChild(overlay);
    }, 1000);
  }
  