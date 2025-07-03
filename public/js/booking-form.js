// Form submission handler function
function submitBookingForm(formData) {
// Validate form data
if (!validateBookingForm(formData)) {
return false;
}

// Prepare data for submission
const submitData = new FormData();

// Add basic fields
submitData.append('hall_id', formData.hallId);
submitData.append('hall_name', formData.hallName);
submitData.append('hall_booking_date', formData.hallBookingDate);
submitData.append('selected_package', formData.package.id);
submitData.append('package_price', formData.package.price);

// Add customization options
submitData.append('final_guest_count', formData.customization.guestCount);
submitData.append('final_wedding_type', formData.customization.weddingType);
submitData.append('final_wedding_type_timeslot', formData.customization.weddingTypeTimeSlot);
submitData.append('final_catholic_day1_date', formData.customization.catholicDay1Date || '');
submitData.append('final_catholic_day2_date', formData.customization.catholicDay2Date || '');

// Add decoration and catering options
submitData.append('final_decorations_additional', JSON.stringify(formData.customization.decorations.additional || []));
submitData.append('final_catering_menu', formData.customization.catering.selectedMenuId || '');
submitData.append('final_catering_custom_items', JSON.stringify(formData.customization.catering.custom || []));
submitData.append('final_catering_supplementary', JSON.stringify(formData.customization.catering.supplementary || []));
submitData.append('final_additional_services', JSON.stringify(formData.customization.additionalServices.selected || []));
// Auto-fix: submit selected_menu_id (integer)
if (window.cateringMenusData && formData.customization.catering.selectedMenuId) {
const menuObj = window.cateringMenusData.find(m => m.id === formData.customization.catering.selectedMenuId);
if (menuObj && menuObj.db_id) {
submitData.append('selected_menu_id', menuObj.db_id);
}
}

// Add contact information
submitData.append('customer_name', formData.contact.name);
submitData.append('customer_email', formData.contact.email);
submitData.append('customer_phone', formData.contact.phone);
submitData.append('visit_purpose', formData.contact.visitPurpose || '');
submitData.append('visit_purpose_other', formData.contact.visitPurposeOther || '');
submitData.append('special_requests', formData.contact.specialRequests || '');

// Add visit information
submitData.append('preferred_visit_date', formData.visitDate);
submitData.append('preferred_visit_time', formData.visitTime);


// Add wedding details with format validation
    const ceremonyTime = formData.weddingDetails.ceremonyTime || '';
    const receptionTime = formData.weddingDetails.receptionTime || '';
    const timeRegex = /^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/;
    if (ceremonyTime && !timeRegex.test(ceremonyTime)) {
        console.warn('Invalid ceremony time format:', ceremonyTime);
        return false; // Prevent submission
    }
    if (receptionTime && !timeRegex.test(receptionTime)) {
        console.warn('Invalid reception time format:', receptionTime);
        return false; // Prevent submission
    }
    submitData.append('wedding_ceremony_time', ceremonyTime);
    submitData.append('wedding_reception_time', receptionTime);


// Add selected_menu_id with mapping and validation
    if (window.cateringMenusData && formData.customization.catering.selectedMenuId) {
        const menuObj = window.cateringMenusData.find(m => m.id === formData.customization.catering.selectedMenuId);
        if (menuObj && menuObj.db_id) {
            submitData.append('selected_menu_id', menuObj.db_id);
        } else {
            console.warn('Invalid catering menu ID:', formData.customization.catering.selectedMenuId);
            return false; // Prevent submission with invalid menu
        }
    } else {
        console.warn('cateringMenusData or selectedMenuId is undefined');
        return false; // Prevent submission if data is missing
    }


// Add wedding details
submitData.append('wedding_groom_name', formData.weddingDetails.groomName);
submitData.append('wedding_bride_name', formData.weddingDetails.brideName);
submitData.append('wedding_groom_email', formData.weddingDetails.groomEmail || '');
submitData.append('wedding_bride_email', formData.weddingDetails.brideEmail || '');
submitData.append('wedding_groom_phone', formData.weddingDetails.groomPhone || '');
submitData.append('wedding_bride_phone', formData.weddingDetails.bridePhone || '');
submitData.append('wedding_date_final', formData.weddingDetails.weddingDate);
submitData.append('wedding_alt_date1', formData.weddingDetails.alternativeDate1 || '');
submitData.append('wedding_alt_date2', formData.weddingDetails.alternativeDate2 || '');
submitData.append('wedding_ceremony_time', formData.weddingDetails.ceremonyTime || '');
submitData.append('wedding_reception_time', formData.weddingDetails.receptionTime || '');
submitData.append('wedding_additional_notes', formData.weddingDetails.additionalNotes || '');
submitData.append('terms_agreed', formData.weddingDetails.termsAgreed ? '1' : '0');
submitData.append('privacy_agreed', formData.weddingDetails.privacyAgreed ? '1' : '0');

// Add CSRF token
submitData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

return submitData;
}

// Form validation function
function validateBookingForm(formData) {
    const errors = [];

    // Validate basic required fields
    if (!formData.hallId) errors.push('Please select a hall');
    if (!formData.hallBookingDate) errors.push('Please select a booking date');
    if (!formData.package.id) errors.push('Please select a package');

    // Validate guest count
    const guestCount = parseInt(formData.customization.guestCount);
    if (!guestCount || guestCount < 10 || guestCount > 1000) {
        errors.push('Guest count must be between 10 and 1000');
    }

    // Validate wedding type related fields
    if (!formData.customization.weddingType) {
        errors.push('Please select a wedding type');
    } else if (formData.customization.weddingType === 'Catholic Wedding') {
        if (!formData.customization.catholicDay1Date) errors.push('Please select church ceremony date');
        if (!formData.customization.catholicDay2Date) errors.push('Please select reception date');
    } else {
        if (!formData.customization.weddingTypeTimeSlot) errors.push('Please select a time slot');
    }

    // Validate contact information
    if (!formData.contact.name) errors.push('Please enter contact name');
    if (!formData.contact.email) errors.push('Please enter contact email');
    if (!formData.contact.phone) errors.push('Please enter contact phone');

    // Validate visit information
    if (!formData.visitDate) errors.push('Please select preferred visit date');
    if (!formData.visitTime) errors.push('Please select preferred visit time');

    // Validate couple information
    if (!formData.weddingDetails.groomName) errors.push('Please enter groom\'s name');
    if (!formData.weddingDetails.brideName) errors.push('Please enter bride\'s name');
    if (!formData.weddingDetails.weddingDate) errors.push('Please select wedding date');

    // Validate wedding date is a future date
    const today = new Date().toISOString().split('T')[0];
    if (formData.weddingDetails.weddingDate <= today) {
        errors.push('The wedding date must be a future date');
    }

    // Validate catering menu
    if (!formData.customization.catering.selectedMenuId || !window.cateringMenusData || !window.cateringMenusData.some(m => m.id === formData.customization.catering.selectedMenuId)) {
        errors.push('Please select a valid catering menu');
    }

    // Validate time format and order
    const startTime = formData.weddingDetails.ceremonyTime || formData.start_time;
    const endTime = formData.weddingDetails.receptionTime || formData.end_time;
    const timeRegex = /^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/; // HH:MM format (00:00 - 23:59)
    if (startTime && !timeRegex.test(startTime)) {
        errors.push('Start time must be in HH:MM format (e.g., 14:00)');
    }
    if (endTime && !timeRegex.test(endTime)) {
        errors.push('End time must be in HH:MM format (e.g., 15:00)');
    }
    if (startTime && endTime) {
        const start = new Date(`1970-01-01 ${startTime}`);
        const end = new Date(`1970-01-01 ${endTime}`);
        if (end <= start) {
            errors.push('The end time must be after the start time');
        }
    }

    // Validate agreement acceptance
    if (!formData.weddingDetails.termsAgreed) errors.push('Please accept the terms and conditions');
    if (!formData.weddingDetails.privacyAgreed) errors.push('Please accept the privacy policy');

    // If there are errors, show error messages
    if (errors.length > 0) {
        showErrorMessages(errors);
        return false;
    }

    return true;
}

// Show error messages function
function showErrorMessages(messages) {
const errorDiv = document.createElement('div');
errorDiv.className = 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4';
errorDiv.role = 'alert';
errorDiv.innerHTML = '<ul class="list-disc list-inside">' + 
messages.map(msg => '<li>' + msg + '</li>').join('') + 
'</ul>';

const form = document.getElementById('booking-form');
form.insertBefore(errorDiv, form.firstChild);

// Automatically remove error messages after 5 seconds
setTimeout(() => {
errorDiv.remove();
}, 5000);
}

// Show success message function
function showSuccessMessage(message) {
const successDiv = document.createElement('div');
successDiv.className = 'bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4';
successDiv.role = 'alert';
successDiv.innerHTML = message;

const form = document.getElementById('booking-form');
form.insertBefore(successDiv, form.firstChild);

// Automatically remove success message after 5 seconds
setTimeout(() => {
successDiv.remove();
}, 5000);
}

// Export functions for use in other modules
window.bookingForm = {
submit: submitBookingForm,
validate: validateBookingForm,
showError: showErrorMessages,
showSuccess: showSuccessMessage
};
