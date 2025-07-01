import argparse
from gtts import gTTS
import os
import sys

def generate_voice_files(output_dir):
    messages = {
        "morning_initial": "Good morning. It's time to mark your attendance.",
        "morning_reminder": "Reminder: Please mark your morning attendance.",
        "afternoon_initial": "Good afternoon. Time to mark attendance.",
        "afternoon_reminder": "Reminder: Please mark your afternoon attendance."
    }

    os.makedirs(output_dir, exist_ok=True)

    for filename, text in messages.items():
        try:
            full_path = os.path.join(output_dir, f"{filename}.mp3")
            tts = gTTS(text=text, lang='en', slow=False)
            tts.save(full_path)
            print(f"Generated: {full_path}")
        except Exception as e:
            print(f"Error generating {filename}.mp3: {str(e)}", file=sys.stderr)

if __name__ == "__main__":
    parser = argparse.ArgumentParser()
    parser.add_argument('--output', required=True, help='Output directory for MP3 files')
    args = parser.parse_args()

    generate_voice_files(args.output)
