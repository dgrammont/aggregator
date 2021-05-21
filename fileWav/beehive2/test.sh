#sox -n s1a.wav synth 3 sine 660-2640
#sox -n s1b.wav synth 3 sine 1320-5280
#sox -n s1c.wav synth 3 sine 1980-7920

sox -n -b 16 -r 48000 s1a.wav synth 3 sine 660-2640 vol -1dB
sox -n -b 16 -r 48000 s1b.wav synth 3 sine 1320-5280 vol -1dB
sox -n -b 16 -r 48000 s1c.wav synth 3 sine 1980-7920 vol -1dB
sox -m s1a.wav s1b.wav s1c.wav s1.wav
sox s1.wav  -n spectrogram -o s1.png
sox s1a.wav -n spectrogram -o s1a.png
sox s1b.wav -n spectrogram -o s1b.png
sox s1c.wav -n spectrogram -o s1c.png
