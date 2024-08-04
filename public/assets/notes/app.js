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
    const noteSearch = notes.querySelector("nav > .flex > input");
    const notesNavBt = notes.querySelector("nav > .flex > button");
    const notesNav = notes.querySelector("nav ul");
    const notesList = notes.querySelector("& > .grid");
    let isUpdating = false;
    let isMenu = false;

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

      const content = addContent.value.trim();
      const cat = addCat.value.trim();
      if (!content) return;

      const res = await fetchFn.post(location.pathname, { add: content, cat });
      if (res) {
        refreshDOM(res);
        addContent.value = "";
        addContent.focus();
      } else {
        dom.notify("Erreur lors de l'ajout.", "error");
        addContent.focus();
      }
    }
    /**
     * Edit Note
     * @param {MouseEvent} e
     */
    async function editNote(e) {
      const target = e.target;
      if (!(target instanceof HTMLElement)) return;

      switch (true) {
        case target.matches(".bt.update"):
          const article = target.closest("article");
          if (!(article instanceof HTMLElement)) return;

          const upId = article.dataset.id.trim();
          const cat = article.querySelector(".cat");
          const content = article.querySelector(".content");
          if (!upId) return;

          isUpdating = !isUpdating;
          if (isUpdating) {
            dom.modClass(article, "updating");
            cat.setAttribute("contenteditable", true);
            content.setAttribute("contenteditable", true);
            target.textContent = "Valider";
          } else {
            dom.modClass(article, "updating", "del");
            cat.removeAttribute("contenteditable");
            content.removeAttribute("contenteditable");
            target.textContent = "Modifier";
            const res = await fetchFn.post(location.pathname, {
              update: upId,
              content: content.textContent,
              cat: cat.textContent,
            });
            if (!res) {
              dom.notify("Erreur lors de la mise à jour.", "error");
            }
          }
          break;
        case target.matches(".bt.delete"):
          const delId = target.closest("article[data-id]").dataset.id.trim();
          if (!delId) return;
          const res = await fetchFn.post(location.pathname, { delete: delId });
          if (res) {
            location.assign(location.href);
          } else {
            dom.notify("Erreur lors de la suppression.", "error");
          }
          break;
      }
    }

    async function search() {
      const searchVal = noteSearch.value.trim();
      const res = await fetchFn.post(location.pathname, { search: searchVal });
      if (res) {
        dom.removeChildren(notesList);
        dom.prependHtml(notesList, res);
      } else {
        dom.notify("Aucun résultat.");
      }
    }
    function togMenu() {
      isMenu = !isMenu;
      if (isMenu) {
        dom.modClass(notesNavBt, "active");
        dom.modClass(notesNav, "visible");
      } else {
        dom.modClass(notesNavBt, "active", "del");
        dom.modClass(notesNav, "visible", "del");
      }
    }

    form.addEventListener("submit", addNote);
    noteSearch.addEventListener("input", search);
    notesNavBt.addEventListener("click", togMenu);
    notesList.addEventListener("click", editNote);
  } catch (err) {
    return dom.error(err);
  }
}
