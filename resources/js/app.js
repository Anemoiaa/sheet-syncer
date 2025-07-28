import './bootstrap';


function initStatusUpdater() {
    document.querySelectorAll('.status-select').forEach(select => {
        select.addEventListener('change', async (e) => {
            const id = e.target.dataset.id;
            const newStatus = e.target.value;

            try {
                const response = await fetch(`/api/update-status/${id}`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ status: newStatus })
                });

                alert('Статус успешно обновлен')
            } catch (error) {
                alert('Не удалось обновить статус');
            }
        });
    });
}

function initFilters() {
    // TODO implement
}

document.addEventListener('DOMContentLoaded', () => {
    initStatusUpdater();
    initFilters();
});
