import {
	TextControl,
	Flex,
	FlexBlock,
	FlexItem,
	Button,
	Icon,
	PanelBody,
	PanelRow,
	ColorPicker,
} from "@wordpress/components";
import {
	InspectorControls,
	BlockControls,
	AlignmentToolbar,
	useBlockProps,
} from "@wordpress/block-editor";
import { ChromePicker } from "react-color";
import "./index.scss";

// immediately invoked function expression (IIFE)
(function () {
	let locked = false;

	// call it whenever anything is being changed.
	wp.data.subscribe(function () {
		const results = wp.data
			.select("core/block-editor")
			.getBlocks()
			.filter((block) => {
				return (
					block.name == "ourplugin/are-you-paying-attention" &&
					block.attributes.correctAnswer == undefined
				);
			});
		if (results.length && locked == false) {
			locked = true;
			wp.data.dispatch("core/editor").lockPostSaving("noanswer");
		}
		if (!results.length && locked) {
			locked = false;
			wp.data.dispatch("core/editor").unlockPostSaving("noanswer");
		}
	});
})();

// wp itserlf is going to add something called wp to the browser's global scope.
wp.blocks.registerBlockType("ourplugin/are-you-paying-attention", {
	// title: "Are you Paying Attention?",
	// icon: "smiley",
	// category: "common",
	// attributes: {
	// 	question: { type: "string" },
	// 	answers: { type: "array", default: [""] },
	// 	correctAnswer: { type: "number", default: undefined },
	// 	bgColor: { type: "string", default: "#EBEBEB" },
	// 	theAlignment: { type: "string", default: "left" },
	// },
	// description: "Give your audience a chance to prove their comprehension.",
	// example: {
	// 	attributes: {
	// 		question: "What is my name?",
	// 		correctAnswer: 3,
	// 		answers: ["Meowsalot", "Barksalot", "Purrsloud"],
	// 		theAlignment: "center",
	// 		bgColor: "#CFE8F1",
	// 	},
	// },
	edit: EditComponent,
	save: function (props) {
		return null;
	},
});

// In React function name starts with UpperCase
function EditComponent(props) {
	// As adopting block.json and wp default wrapping element is gone, add blockProps
	const blockProps = useBlockProps({
		className: "paying-attention-edit-block",
		style: { backgroundColor: props.attributes.bgColor },
	});

	function updateQuestion(value) {
		props.setAttributes({ question: value });
	}

	function deleteAnswer(indexToDelete) {
		const newAnswers = props.attributes.answers.filter(function (x, index) {
			return index != indexToDelete;
		});
		props.setAttributes({ answers: newAnswers });
		if (indexToDelete == props.attributes.correctAnswer) {
			props.setAttributes({ correctAnswer: undefined });
		}
	}

	function markAsCorrect(index) {
		props.setAttributes({ correctAnswer: index });
	}

	return (
		// TextControl - WP components
		<div {...blockProps}>
			<BlockControls>
				<AlignmentToolbar
					value={props.attributes.theAlignment}
					onChange={(x) => props.setAttributes({ theAlignment: x })}
				/>
			</BlockControls>
			<InspectorControls>
				<PanelBody title="Background Color" initialOpen={true}>
					<PanelRow>
						<ColorPicker
							color={props.attributes.bgColor}
							onChangeComplete={(x) => props.setAttributes({ bgColor: x.hex })}
							disableAlpha={true}
						/>
					</PanelRow>
				</PanelBody>
			</InspectorControls>
			<TextControl
				style={{ fontSize: "20px" }}
				label="Question:"
				value={props.attributes.question}
				onChange={updateQuestion}
			/>
			<p style={{ fontSize: "13px", margin: "20px 0 8px 0" }}>Answer:</p>

			{props.attributes.answers.map((answer, index) => {
				return (
					<Flex>
						<FlexBlock>
							{/* Text Control passes the value */}
							<TextControl
								value={answer}
								autoFocus={answer == undefined}
								onChange={(newValue) => {
									const newAnswers = [...props.attributes.answers];
									newAnswers[index] = newValue;
									props.setAttributes({ answers: newAnswers });
								}}
							/>
						</FlexBlock>
						<FlexItem>
							<Button onClick={() => markAsCorrect(index)}>
								<Icon
									icon={
										props.attributes.correctAnswer == index ? "star-filled" : "star-empty"
									}
									className="mark-as-correct"
								/>
							</Button>
						</FlexItem>
						<FlexItem>
							<Button
								isLink
								className="attention-delete"
								onClick={() => deleteAnswer(index)}
							>
								Delete
							</Button>
						</FlexItem>
					</Flex>
				);
			})}

			<Button
				isPrimary
				onClick={() => {
					const newAnswers = [...props.attributes.answers, undefined];
					props.setAttributes({ answers: newAnswers });
				}}
			>
				Add another answer
			</Button>
		</div>
	);
}
