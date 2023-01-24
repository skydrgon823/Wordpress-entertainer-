import $ from 'jquery';

const portalId = '6275621';
const formId = '0e8807f8-2ac3-4664-b742-44552bfa09e2';
const formSubmissionUrl = `https://api.hsforms.com/submissions/v3/integration/submit/${portalId}/${formId}`;

export function submitFeedbackForm(formSelector) {
  const formSubmissionPayload = {
    fields: $(formSelector).serializeArray(),
    skipValidation: true,
  };

  return new Promise((resolve, reject) => {
    $.ajax({
      type: 'POST',
      url: formSubmissionUrl,
      contentType: 'application/json',
      data: JSON.stringify(formSubmissionPayload),
      success: resolve,
      error: reject,
    });
  });
}
