document.addEventListener('alpine:init', () => {
    Alpine.store('print', {
        ticket(data) {
            const iframe = document.getElementById('print-frame');
            const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;

            iframeDoc.open();
            iframeDoc.write('<html><head><title>Print</title>');
            iframeDoc.write(`
                <style>
                    @media print {
                        .no-print { display: none; }
                        .ticket-container{
                            width: 80mm;
                            height: auto;
                            border: none;
                            text-align: center;
                        }
                    }
                    .ticket-container{
                        text-align: center;
                    }
                    .title{
                        font-weight: bold;
                    }
                    hr{
                        border: 1px solid black;
                    }
                    .font-bold{
                        font-weight: bold;
                    }
                    .font-capital{
                          text-transform: uppercase;
                          font-size: 16px;
                    }
                    .queue-number{
                        font-size: 7em;
                        font-weight: bold;
                        padding: 0;
                        margin: 0;
                    }
                    .notes{
                        font-style: italic;
                        font-size: 12px;
                        font-weight: bold;
                    }
                </style>`);
            iframeDoc.write('</head><body>');
            iframeDoc.write(`
                <div class="ticket-container">
                    <p class="title">Faskes Tingkat Lanjut</p>
                    <p class="title">RSU Universitas Muhammadiyah Malang</p>
                    <p>${data.register_date}</p>
                    <p class="font-capital">${data.patient_name} - ${data.rm_code}</p>
                    <hr/>
                    <p>Kode Booking : <span class="font-bold">${data.booking_code}</span></p>
                    <p class="font-bold" style="font-weight: bold;">Harap check-in 30 menit sebelum praktik</p>
                    <p class="queue-number">${data.queue_number}</p>
                    <p class="font-bold">Admisi/TPP</p>
                    <hr/>
                    <p>Antrean admisi yang belum dilayani : ${data.remaining_queue}</p>
                    <p class="font-capital">${data.poly_name}  - ${data.doctor_name}</p>
                     <small class="notes" style="font-style: italic">*) Kode antrian A akan dipanggil pada jam 07:00 - selesai.</small>
                     <br>
                     <small class="notes">*) Kode antrian B akan dipanggil pada jam 12:00 - selesai.</small>
                </div>
            `);

            iframeDoc.write('</body></html>');
            iframeDoc.close();

            iframe.contentWindow.focus();
            iframe.contentWindow.print();
            Livewire.navigate('/antrean')
        }
    });

})
