import "./bootstrap";


document.addEventListener("DOMContentLoaded", () => {
    const userMeta = document.head.querySelector('meta[name="user-id"]');
    const container = document.getElementById("notification-list");
    const countBadge = document.getElementById("notification-count");
    let count = parseInt(countBadge?.textContent || 0);

    if (userMeta && container) {
        const userId = userMeta.content;

        window.Echo.private(`App.Models.User.${userId}`).notification(
            (notification) => {
                console.log("Realtime with Pusher:", notification);

                // Build the new notification HTML
                const html = `
                    <a href="javascript:void(0)" class="px-24 py-12 d-flex align-items-start gap-3 mb-2 justify-content-between">
                        <div class="text-black hover-bg-transparent hover-text-primary d-flex align-items-center gap-3"> 
                            <span class="w-44-px h-44-px bg-success-subtle text-success-main rounded-circle d-flex justify-content-center align-items-center flex-shrink-0">
                                <iconify-icon icon="${
                                    notification.icon ??
                                    "bitcoin-icons:verify-outline"
                                }" class="icon text-xxl"></iconify-icon>
                            </span> 
                            <div>
                                <h6 class="text-md fw-semibold mb-4">${
                                    notification.title ?? "Notification"
                                }</h6>
                                <p class="mb-0 text-sm text-w-200-px">${
                                    notification.message
                                }</p>
                            </div>
                        </div>
                        <span class="text-sm text-secondary-light flex-shrink-0">just now</span>
                    </a>
                `;

                // Insert new notification at the top of the list
                container.insertAdjacentHTML("afterbegin", html);

                // Increment the badge counter
                count++;
                countBadge.textContent = count;
                countBadge.classList.remove("d-none");
            }
        );
    }

    // Optional: reset badge when user opens dropdown
    
});