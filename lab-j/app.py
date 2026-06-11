import sqlite3
from flask import Flask, render_template, request, redirect, url_for, abort

app = Flask(__name__)
DATABASE = "data.db"


def get_connection():
    conn = sqlite3.connect(DATABASE)
    conn.row_factory = sqlite3.Row
    return conn


def init_db():
    conn = get_connection()
    conn.execute("""
        CREATE TABLE IF NOT EXISTS pizza (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            size TEXT NOT NULL
        )
    """)

    conn.execute("""
        INSERT INTO pizza (name, size)
        SELECT 'Margherita', 'M'
        WHERE NOT EXISTS (SELECT 1 FROM pizza)
    """)

    conn.execute("""
        INSERT INTO pizza (name, size)
        SELECT 'Pepperoni', 'L'
        WHERE (SELECT COUNT(*) FROM pizza) = 1
    """)

    conn.commit()
    conn.close()


@app.route("/")
def home():
    return redirect(url_for("pizza_index"))

@app.route("/pizzas")
def pizza_index():
    conn = get_connection()
    pizzas = conn.execute("SELECT * FROM pizza ORDER BY id").fetchall()
    conn.close()
    return render_template("pizzas/index.html", pizzas=pizzas)

@app.route("/pizzas/new", methods=["GET", "POST"])
def pizza_new():
    if request.method == "POST":
        name = request.form["name"]
        size = request.form["size"]
        conn = get_connection()
        conn.execute("INSERT INTO pizza (name, size) VALUES (?, ?)", (name, size))
        conn.commit()
        conn.close()
        return redirect(url_for("pizza_index"))
    return render_template("pizzas/new.html")

@app.route("/pizzas/<int:pizza_id>")
def pizza_show(pizza_id):
    conn = get_connection()
    pizza = conn.execute("SELECT * FROM pizza WHERE id = ?", (pizza_id,)).fetchone()
    conn.close()
    if pizza is None:
        abort(404)
    return render_template("pizzas/show.html", pizza=pizza)

@app.route("/pizzas/<int:pizza_id>/edit", methods=["GET", "POST"])
def pizza_edit(pizza_id):
    conn = get_connection()
    pizza = conn.execute("SELECT * FROM pizza WHERE id = ?", (pizza_id,)).fetchone()
    if pizza is None:
        conn.close()
        abort(404)
    if request.method == "POST":
        name = request.form["name"]
        size = request.form["size"]
        conn.execute(
            "UPDATE pizza SET name = ?, size = ? WHERE id = ?",
            (name, size, pizza_id)
        )
        conn.commit()
        conn.close()
        return redirect(url_for("pizza_show", pizza_id=pizza_id))
    conn.close()
    return render_template("pizzas/edit.html", pizza=pizza)


@app.route("/pizzas/<int:pizza_id>/delete", methods=["POST"])
def pizza_delete(pizza_id):
    conn = get_connection()
    conn.execute("DELETE FROM pizza WHERE id = ?", (pizza_id,))
    conn.commit()
    conn.close()
    return redirect(url_for("pizza_index"))

if __name__ == "__main__":
    init_db()
    app.run(port=57749, debug=True)