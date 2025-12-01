# chatbot.py

from flask import Flask, request, jsonify

app = Flask(__name__)


def chatbot_response(message):
    message = message.lower()
    if "hello" in message or "hi" in message:
        return "Hello! How can I help you today?"
    elif "your name" in message:
        return "I'm your friendly chatbot 😊"
    elif "bye" in message:
        return "Goodbye! Have a great day!"
    else:
        return "I'm sorry, I don't understand that. Can you rephrase?"
    
rendertemplate('home.html', 'packages.html', 'book.html', 'pay.html')
@app.route("/chat", methods=["POST"])
def chat():
    user_message = request.json.get("message")
    response = chatbot_response(user_message)
    return jsonify({"reply": response})

if __name__ == "__main__":
    app.run(debug=True)
