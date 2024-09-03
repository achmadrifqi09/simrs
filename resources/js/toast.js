document.addEventListener('livewire:init', () => {
    Livewire.on('notification', (event) => {

        trigger(event[0].message)
        function  createElement(id, message) {
            const toastContainer = document.querySelector('#toastContainer');
            toastContainer.innerHTML += `
                <div class="shadow-md px-4 py-2.5 bg-white rounded-xl border border-gray-100 flex items-center gap-4" id="${id}">
                    <div class="relative">
                        <div class="aspect-square bg-primary w-10 h-10 flex items-center justify-center text-white rounded-full border-4 border-red-200">
                            <span class="ic-bell"></span>
                        </div>
                    </div>
                    <p class="text-gray-600">${message}</p>
                </div>
            `;
            setTimeout(() => {
                document.querySelector(`#${id}`).classList.remove('enter-toast');
            }, 700)
        }

        function  trigger(message) {
            const id = `toast-${Date.now()}`;
                createElement(id, message);

            setTimeout(() => {
                    removeToast(id)
            }, 5000)

        }

        function  removeToast(id) {
            document.querySelector(`#${id}`).remove();
        }
    });
});
