export function secondsToMinutes(seconds) {
    if (!seconds || isNaN(seconds)) return "0:00";

    const mins = Math.floor(seconds / 60);
    const secs = seconds % 60;

    // always format seconds as 2 digits
    const padded = secs.toString().padStart(2, "0");

    return `${mins}:${padded}`;
}

export function formatTimeUS(date) {
    const d = new Date(date);
    return d.toLocaleString("en-US", {
        year: "numeric",
        month: "short",
        day: "2-digit",
        hour: "2-digit",
        minute: "2-digit",
    });
}

export function formatToHHMMSS(datetime) {
    if (!datetime) return null;

    const date = new Date(datetime);

    const hours = String(date.getHours()).padStart(2, "0");
    const minutes = String(date.getMinutes()).padStart(2, "0");
    const seconds = String(date.getSeconds()).padStart(2, "0");

    return `${hours}:${minutes}:${seconds}`;
}

export function secondsToHHMMSS(seconds) {
  if (!seconds || isNaN(seconds)) return "00:00:00";

  const hours = Math.floor(seconds / 3600);
  const minutes = Math.floor((seconds % 3600) / 60);
  const secs = seconds % 60;

  return [
      hours,
      minutes,
      secs
  ].map(v => v.toString().padStart(2, "0"))
   .join(":");
}
