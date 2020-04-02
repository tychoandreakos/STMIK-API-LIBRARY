import pyperclip
import re

deweyRegex = re.compile(r'''(
    (\d{3})([a-zA-Z&\[\]]*\s*){10}
)''', re.VERBOSE)

text = str(pyperclip.paste())

matches = []


def splitMe(splitText):
    arr = re.split('(\d+)', splitText)
    return {arr[1]: arr[2]}


def getId(text):
    arr = re.split('(\d+)', text)
    return arr[1]


def getString(text):
    arr = re.split('(\d+)', text)
    return arr[2]


for groups in deweyRegex.findall(text):
    matches.append(getString(groups[0]))


if len(matches) > 0:
    print('Copied to clipboard:')
    print(matches)
else:
    print('yaikss, i can\'t copy anything bos!')
