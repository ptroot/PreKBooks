console.log ("loaded tree.js");
// -------------------- Collapsible Tree --------------------
document.addEventListener("DOMContentLoaded", function() {
	const detailPanel = document.getElementById('detailPanel');

	// Add Update Delete page
	const amcDest = document.getElementById('amcDest');
	amcDest.addEventListener('click', () => {
		const detailPanel = document.getElementById('detailPanel');
		console.log('Add Move Delete');

		// Load account details via AJAX
		fetch(`amc_dest.php`)
		.then(response => response.text())
		.then(html => {
			detailPanel.innerHTML = html;
		})
		.catch(err => {
			detailPanel.innerHTML = '<p>Error loading account details.</p>';
			console.error(err);
		});
	});

	document.addEventListener('click', function (e) {
		const group = e.target.closest('.group');
		if (group) {
			group.closest('li').classList.toggle('collapsed');
		}
	});

	// Expand / Collapse All
	const toggleButton = document.getElementById('toggleButton');
	toggleButton.addEventListener('click', () => {
		const collapsedItems = document.querySelectorAll('li.collapsed');
		if (collapsedItems.length > 0) {
			document.querySelectorAll('li.collapsed').forEach(li => li.classList.remove('collapsed'));
			toggleButton.textContent = 'Collapse All';
		} else {
			document.querySelectorAll('ul > li').forEach(li => li.classList.add('collapsed'));
			toggleButton.textContent = 'Expand All';
		}
	});

});

// -------------------- Weather Search --------------------
let searchTimeout = null;

function showWeather() {
	let input = document.getElementById("weatherInput").value;
	console.log('showWeather');

	var timer = null;
    var timedOut = false;
	var timeoutMs = 3000;

	let formattedInput = 'Buffalo+Minnesota';
	if (input !== '') {
		console.log('formatting input');
		// Replace spaces with +
		formattedInput = input.trim().replace(/\s+/g, "+");
	} else {
		input = 'Buffalo+Minnesota';
	}

	// Build the image URL
	let url = `https://wttr.in/${formattedInput}.png?u`;

    function clearTimer() {
        if (timer) {
            clearTimeout(timer);
            timer = null;
        }
    }

    function handleSuccess() {
        if (!timedOut) {
            clearTimer();
            successCallback(url);
        }
    }

    function handleFailure() {
        if (!timedOut) {
            clearTimer();
            timedOut = true; // Prevent multiple callbacks
            failCallback();
        }
    }

    url.onload = handleSuccess;
    url.onerror = handleFailure;
    url.onabort = handleFailure; // For IE compatibility

    // Start the timeout
    timer = setTimeout(function() {
        timedOut = true;
        // Force failure behavior
        url.src = ''; // Attempt to stop loading
    }, timeoutMs);

	// Set it to an <img> element
  document.getElementById("detailPanel").innerHTML = `
    <img src="${url}" alt="Weather for ${input}">
  `;
}

