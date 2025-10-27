
document.addEventListener('DOMContentLoaded', function() {
    // Smooth scroll for appointment button
    const appointmentBtn = document.querySelector('.btn-appointment');
    
    if (appointmentBtn) {
        appointmentBtn.addEventListener('click', function(e) {
            // Add any animation or tracking here if needed
            console.log('Appointment button clicked');
        });
    }

    // Add fade-in animation for cards on scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    // Observe all cards
    const cards = document.querySelectorAll('.info-card, .service-card, .process-section');
    cards.forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(card);
    });
});