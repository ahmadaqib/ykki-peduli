/**
 * Payment Modal Component JavaScript
 * Handles copy to clipboard functionality
 */

/**
 * Initialize copy to clipboard functionality
 * Uses event delegation to work with Livewire dynamic content
 */
(function() {
    'use strict';
    
    let isInitialized = false;
    
    function handleCopyClick(e) {
        // Find the closest copy button (handles clicks on button or its children)
        const button = e.target.closest('.copy-account-btn');
        
        if (!button) return;
        
        // Prevent if already copied or disabled
        if (button.disabled || button.classList.contains('copied')) {
            return;
        }
        
        e.preventDefault();
        e.stopPropagation();
        
        const accountNumber = button.getAttribute('data-account-number');
        const bankName = button.getAttribute('data-bank-name');
        
        if (!accountNumber || !bankName) {
            console.error('Missing data attributes on copy button');
            return;
        }
        
        // Copy to clipboard
        copyToClipboard(accountNumber, button, bankName);
    }
    
    function copyToClipboard(text, button, bankName) {
        // Try modern Clipboard API first
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(text).then(() => {
                window.showCopyFeedback(button, bankName);
            }).catch((err) => {
                console.error('Clipboard API failed:', err);
                window.fallbackCopy(text, button, bankName);
            });
        } else {
            // Fallback for older browsers
            window.fallbackCopy(text, button, bankName);
        }
    }
    
    // Initialize event listener (only once)
    if (!isInitialized) {
        // Use capture phase to ensure we catch the event early
        document.addEventListener('click', handleCopyClick, true);
        isInitialized = true;
    }
    
    // Also listen for Livewire updates
    if (window.Livewire) {
        window.Livewire.hook('morph.updated', () => {
            // Event listener already attached, should work with new content
        });
    }
})();

/**
 * Show copy feedback
 * Must be accessible globally for fallback copy
 */
window.showCopyFeedback = function(button, bankName) {
    // Store original content
    const originalHTML = button.innerHTML;
    const originalClasses = button.className;
    const originalBgColor = button.style.backgroundColor || '';
    
    // Update button to show checkmark only
    button.innerHTML = `
        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
        </svg>
    `;
    button.classList.add('copied');
    
    // Change to green background
    if (button.classList.contains('bg-primary-500') || button.classList.contains('bg-primary-600')) {
        button.classList.remove('bg-primary-500', 'bg-primary-600', 'hover:bg-primary-600', 'hover:bg-primary-700');
        button.classList.add('bg-green-500', 'hover:bg-green-600');
    } else if (button.classList.contains('bg-accent-500') || button.classList.contains('bg-accent-600')) {
        button.classList.remove('bg-accent-500', 'bg-accent-600', 'hover:bg-accent-600');
        button.classList.add('bg-green-500', 'hover:bg-green-600');
    } else {
        button.style.backgroundColor = '#22c55e'; // green-500
    }
    
    button.disabled = true;
    button.style.cursor = 'default';
    
    // Show toast notification
    showToast(`Nomor ${bankName} berhasil disalin!`);
    
    // Reset after 2 seconds
    setTimeout(() => {
        button.innerHTML = originalHTML;
        button.className = originalClasses;
        button.style.backgroundColor = originalBgColor;
        button.disabled = false;
        button.style.cursor = '';
        button.classList.remove('copied');
    }, 2000);
}

/**
 * Fallback copy method for older browsers
 * Must be accessible globally
 */
window.fallbackCopy = function(text, button, bankName) {
    const textArea = document.createElement('textarea');
    textArea.value = text;
    textArea.style.position = 'fixed';
    textArea.style.top = '0';
    textArea.style.left = '0';
    textArea.style.width = '2em';
    textArea.style.height = '2em';
    textArea.style.padding = '0';
    textArea.style.border = 'none';
    textArea.style.outline = 'none';
    textArea.style.boxShadow = 'none';
    textArea.style.background = 'transparent';
    textArea.style.opacity = '0';
    textArea.setAttribute('readonly', '');
    
    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();
    textArea.setSelectionRange(0, 99999); // For mobile devices
    
    try {
        const successful = document.execCommand('copy');
        if (successful) {
            showCopyFeedback(button, bankName);
        } else {
            throw new Error('execCommand copy failed');
        }
    } catch (err) {
        console.error('Failed to copy:', err);
        // Show the text in an alert as last resort
        alert(`Nomor ${bankName}: ${text}\n\nSilakan salin secara manual.`);
    }
    
    document.body.removeChild(textArea);
}

/**
 * Show toast notification
 * Must be accessible globally
 */
window.showToast = function(message) {
    // Remove existing toast if any
    const existingToast = document.querySelector('.copy-toast');
    if (existingToast) {
        existingToast.remove();
    }
    
    // Create toast element
    const toast = document.createElement('div');
    toast.className = 'copy-toast fixed z-[9999] rounded-lg bg-primary-600 px-4 py-3 text-white shadow-2xl flex items-center gap-2';
    
    // Mobile: bottom center, Desktop: bottom right
    const isMobile = window.innerWidth < 640;
    if (isMobile) {
        toast.style.bottom = '1rem';
        toast.style.left = '50%';
        toast.style.maxWidth = 'calc(100% - 2rem)';
        toast.style.width = 'auto';
    } else {
        toast.style.bottom = '1rem';
        toast.style.right = '1rem';
    }
    
    // Add checkmark icon
    toast.innerHTML = `
        <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
        <span class="font-semibold text-sm sm:text-base">${message}</span>
    `;
    
    document.body.appendChild(toast);
    
    // Add slide-in animation
    toast.style.opacity = '0';
    toast.style.transform = isMobile 
        ? 'translateX(-50%) translateY(20px)' 
        : 'translateY(20px)';
    
    // Force reflow
    toast.offsetHeight;
    
    requestAnimationFrame(() => {
        toast.style.transition = 'all 0.3s ease-out';
        toast.style.opacity = '1';
        toast.style.transform = isMobile 
            ? 'translateX(-50%) translateY(0)' 
            : 'translateY(0)';
    });
    
    // Remove toast after 3 seconds
    setTimeout(() => {
        toast.style.transition = 'all 0.3s ease-in';
        toast.style.opacity = '0';
        toast.style.transform = isMobile 
            ? 'translateX(-50%) translateY(20px)' 
            : 'translateY(20px)';
        setTimeout(() => {
            if (toast.parentNode) {
                toast.remove();
            }
        }, 300);
    }, 3000);
}

