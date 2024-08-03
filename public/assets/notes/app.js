import { dom, fetchFn } from "../script.js";

export default async function () {
  try {
    const form = dom.select("form");
    if (!(form instanceof HTMLFormElement)) throw new Error("Invalid form.");
    const addContent = form.querySelector("textarea");
    const addCat = form.querySelector("input");
    const datalist = form.querySelector("datalist");

    const notes = dom.select("#notes");
    if (!(notes instanceof HTMLDivElement)) throw new Error("Invalid notes.");
    const notesNav = notes.querySelector(" nav ul");
    const notesList = notes.querySelector("& > .grid");

    /**
     * Refresh DOM
     * @param {String[]} res
     */
    function refreshDOM(res) {
      dom.removeChildren(datalist);
      dom.removeChildren(notesNav);
      dom.removeChildren(notesList);
      dom.prependHtml(datalist, res[0]);
      dom.prependHtml(notesNav, res[1]);
      dom.prependHtml(notesList, res[2]);
    }

    /**
     * Add note
     * @param {SubmitEvent} e
     */
    async function addNote(e) {
      e.preventDefault();
      const content = addContent.value;
      const cat = addCat.value;
      if (!content) return;

      const res = await fetchFn.post(location.pathname, { content, cat });
      if (res) {
        refreshDOM(res);
        addContent.value = "";
        addContent.focus();
      } else {
        dom.notify("Erreur lors de l'ajout.", "error");
        addContent.focus();
      }
    }

    form.addEventListener("submit", addNote);
  } catch (err) {
    return dom.error(err);
  }
}
