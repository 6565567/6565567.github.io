const styles: Record<string, string> = {
    style1: "public/style-1.css",
    style2: "public/style-2.css"
};

let currentStyle: string = "style1";

function applyStyle(styleKey: string) {
    const oldLink = document.getElementById("dynamic-style");
    if (oldLink) {
        oldLink.remove();
    }
    const link = document.createElement("link");
    link.rel = "stylesheet";
    link.href = styles[styleKey];
    link.id = "dynamic-style";
    document.head.appendChild(link);
    currentStyle = styleKey;
}

function createStyleButtons() {
    const container = document.createElement("div");
    //styluje juz tutaj, mialem problem bo mi jakis element zasłaniał "przeklikiwanie" w stylu 2 i musialem
    //w taki sposob to obejsc po prostu
    container.style.position = "fixed";
    container.style.top = "10px";
    container.style.left = "10px";

    Object.keys(styles).forEach(style => {
        const button = document.createElement("button");
        button.textContent = style;
        button.style.marginRight = "5px";
        button.style.cursor = "pointer";
        button.onclick = () => applyStyle(style);
        container.appendChild(button);
    });
    document.body.appendChild(container);
}

window.onload = () => {
    applyStyle(currentStyle);
    createStyleButtons();
};